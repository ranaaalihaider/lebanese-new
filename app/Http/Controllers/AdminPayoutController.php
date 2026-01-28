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
        $query = Order::where('status', 'completed')
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

        return back()->with('success', 'Payout released successfully for Order #' . $order->order_number);
    }

    public function viewRequests()
    {
        $requests = \App\Models\PayoutRequest::with(['seller.sellerProfile'])
            ->pending()
            ->latestFirst()
            ->paginate(15);

        // Calculate current pending balance for each seller
        foreach ($requests as $request) {
            $request->current_pending = Order::where('seller_id', $request->seller_id)
                ->where('status', 'completed')
                ->where('payout_status', 'pending')
                ->sum('seller_earning');
        }

        return view('admin.payouts.requests', compact('requests'));
    }
}
