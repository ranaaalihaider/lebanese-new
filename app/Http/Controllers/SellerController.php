<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class SellerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->status !== 'active') {
            return view('seller.pending_approval');
        }

        $productsCount = Product::where('seller_id', $user->id)->count();
        $pendingOrders = Order::where('seller_id', $user->id)->where('status', 'pending')->count();
        $earnings = Order::where('seller_id', $user->id)
            ->where('status', 'completed')
            ->sum('seller_earning');
        $storeName = $user->sellerProfile->store_name ?? 'Your Store';

        $recentOrders = Order::where('seller_id', $user->id)
            ->with(['product', 'buyer'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('productsCount', 'pendingOrders', 'earnings', 'storeName', 'recentOrders'));
    }

    public function orders($status = 'all')
    {
        $query = \App\Models\Order::where('seller_id', Auth::id())->with('product');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);
        return view('seller.orders.index', compact('orders', 'status'));
    }

    public function show(\App\Models\Order $order)
    {
        if ($order->seller_id !== Auth::id()) {
            abort(403);
        }
        return view('seller.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, \App\Models\Order $order)
    {
        if ($order->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,handed_to_courier,completed',
        ]);

        $order->update(['status' => $request->status]);

        // Notify Buyer
        $buyer = \App\Models\User::find($order->buyer_id);
        if ($buyer) {
            $buyer->notify(new \App\Notifications\OrderStatusChangedNotification($order, $request->status));
        }

        return back()->with('success', 'Order status updated to ' . str_replace('_', ' ', $request->status));
    }

    public function earnings(Request $request)
    {
        $query = \App\Models\Order::where('seller_id', Auth::id())
            ->where('status', 'completed')
            ->with('product');

        // Filter by Payout Status
        if ($request->has('payout_status') && $request->payout_status !== 'all') {
            $query->where('payout_status', $request->payout_status);
        }

        // Search by Order Number or Product Name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Date Range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->paginate(15);

        $totalEarnings = \App\Models\Order::where('seller_id', Auth::id())
            ->where('status', 'completed')
            ->sum('seller_earning');

        $pendingPayouts = \App\Models\Order::where('seller_id', Auth::id())
            ->where('status', 'completed')
            ->where('payout_status', 'pending')
            ->sum('seller_earning');

        // Check for existing pending payout request (for button display)
        $payoutRequest = \App\Models\PayoutRequest::where('seller_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        // Fetch all payout requests for history
        $payoutRequests = \App\Models\PayoutRequest::where('seller_id', Auth::id())
            ->with('processedBy')
            ->latest('requested_at')
            ->get();

        return view('seller.earnings', compact('orders', 'totalEarnings', 'pendingPayouts', 'payoutRequest', 'payoutRequests'));

    }

    public function requestPayout()
    {
        $user = Auth::user();

        // Calculate pending balance
        $pendingPayouts = Order::where('seller_id', $user->id)
            ->where('status', 'completed')
            ->where('payout_status', 'pending')
            ->sum('seller_earning');

        // Validate pending balance
        if ($pendingPayouts <= 0) {
            return back()->with('error', 'No pending balance available for payout request.');
        }

        // Check if there's already a pending request
        $existingRequest = \App\Models\PayoutRequest::where('seller_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending payout request. Please wait for it to be processed.');
        }

        // Create payout request
        $payoutRequest = \App\Models\PayoutRequest::create([
            'seller_id' => $user->id,
            'requested_amount' => $pendingPayouts,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\PayoutRequestNotification($payoutRequest));
        }

        return back()->with('success', 'Payout request submitted successfully! Admin will review it soon.');
    }

}
