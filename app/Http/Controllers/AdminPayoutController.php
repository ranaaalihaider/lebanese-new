<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['seller.sellerProfile'])
            ->where('status', 'completed')
            ->whereNotNull('seller_earning');

        // Filter by Seller
        if ($request->has('seller_id') && $request->seller_id != 'all') {
            $query->where('seller_id', $request->seller_id);
        }

        // Filter by Payout Status
        if ($request->has('payout_status') && $request->payout_status != 'all') {
            $query->where('payout_status', $request->payout_status);
        } else {
            // Default to showing pending payouts first
            $query->orderByRaw("FIELD(payout_status, 'pending', 'paid') ASC");
        }

        // Search by Order Number
        if ($request->has('search') && !empty($request->search)) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Filter by Date Range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->paginate(15);
        $sellers = User::where('role', 'seller')->get();

        // Calculate Stats
        $pendingPayouts = Order::where('status', 'completed')
            ->where('payout_status', 'pending')
            ->sum('seller_earning');

        $totalPaidOut = Order::where('status', 'completed')
            ->where('payout_status', 'paid')
            ->sum('seller_earning');

        return view('admin.payouts.index', compact('orders', 'sellers', 'pendingPayouts', 'totalPaidOut'));
    }

    public function payout(Request $request, Order $order)
    {
        $request->validate([
            'payout_method' => 'required|string',
            'payout_transaction_id' => 'required|string',
        ]);

        if ($order->status !== 'completed') {
            return back()->with('error', 'Order must be completed before payout.');
        }

        if ($order->payout_status === 'paid') {
            return back()->with('error', 'Order is already paid out.');
        }

        $payoutDetails = [];
        if ($request->payout_method === 'Bank Transfer' && $order->seller->sellerProfile) {
            $payoutDetails = [
                'bank_name' => $order->seller->sellerProfile->bank_name,
                'account_title' => $order->seller->sellerProfile->account_title,
                'account_number' => $order->seller->sellerProfile->account_number,
            ];
        }

        $order->update([
            'payout_status' => 'paid',
            'payout_amount' => $order->seller_earning,
            'payout_date' => now(),
            'payout_method' => $request->payout_method,
            'payout_transaction_id' => $request->payout_transaction_id,
            'payout_details' => $payoutDetails,
        ]);

        // Check if seller has any more pending payouts
        $remainingPending = Order::where('seller_id', $order->seller_id)
            ->where('status', 'completed')
            ->where('payout_status', 'pending')
            ->count();

        if ($remainingPending === 0) {
            // Find and close the pending payout request
            $payoutRequest = \App\Models\PayoutRequest::where('seller_id', $order->seller_id)
                ->where('status', 'pending')
                ->first();

            if ($payoutRequest) {
                $payoutRequest->update([
                    'status' => 'approved',
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                ]);

                // Notify Seller
                $seller = \App\Models\User::find($order->seller_id);
                if ($seller) {
                    $seller->notify(new \App\Notifications\PayoutStatusUpdatedNotification($payoutRequest));
                }
            }
        }

        return back()->with('success', 'Payout released successfully for Order #' . $order->order_number);
    }

    public function viewRequests(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = \App\Models\PayoutRequest::with(['seller.sellerProfile', 'processedBy'])
            ->latestFirst();

        // Filter by status
        if ($status === 'pending') {
            $query->where('status', 'pending')
                ->whereHas('seller.sellerOrders', function ($q) {
                    $q->where('status', 'completed')
                        ->where('payout_status', 'pending');
                });
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }

        $requests = $query->paginate(15);

        // Calculate current pending balance for each seller
        foreach ($requests as $payoutRequest) {
            $payoutRequest->current_pending = Order::where('seller_id', $payoutRequest->seller_id)
                ->where('status', 'completed')
                ->where('payout_status', 'pending')
                ->sum('seller_earning');
        }

        return view('admin.payouts.requests', compact('requests', 'status'));
    }

    public function rejectPayoutRequest(Request $request, \App\Models\PayoutRequest $payoutRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($payoutRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $payoutRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Notify Seller
        $seller = \App\Models\User::find($payoutRequest->seller_id);
        if ($seller) {
            $seller->notify(new \App\Notifications\PayoutStatusUpdatedNotification($payoutRequest));
        }

        return back()->with('success', 'Payout request rejected successfully.');
    }
}
