<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::where('buyer_id', auth()->id())
            ->with(['product.seller.sellerProfile', 'review']) // Eager load review to check status
            ->latest()
            ->paginate(10);
        return view('buyer.orders.index', compact('orders'));
    }

    public function show(\App\Models\Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }
        $order->load(['product.seller.sellerProfile', 'review']);
        return view('buyer.orders.show', compact('order'));
    }

    public function cancel(\App\Models\Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock if necessary (optional, depending on business logic)
        // $order->product->increment('stock', $order->quantity);

        return redirect()->route('buyer.orders.index')->with('success', 'Order cancelled successfully.');
    }
}
