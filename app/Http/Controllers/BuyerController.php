<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductType;

class BuyerController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)->with(['seller.sellerProfile'])->latest()->take(8)->get();
        $types = ProductType::all();

        // Get buyer orders for dashboard (if authenticated)
        $orders = auth()->check()
            ? \App\Models\Order::where('buyer_id', auth()->id())
                ->with(['product', 'seller.sellerProfile'])
                ->orderBy('created_at', 'desc')
                ->get()
            : collect(); // Empty collection for guests

        return view('buyer.home', compact('featuredProducts', 'types', 'orders'));
    }

    public function stores()
    {
        $stores = User::where('role', 'seller')
            ->where('status', 'active')
            ->with('sellerProfile')
            ->get();
        return view('buyer.stores', compact('stores'));
    }

    public function showStore($id)
    {
        $seller = User::where('role', 'seller')->with('sellerProfile')->findOrFail($id);
        // Assuming products relationship exists on User model
        $products = $seller->products()->where('is_active', true)->latest()->get();
        return view('buyer.store_details', compact('seller', 'products'));
    }

    public function showProduct(Product $product)
    {
        // Allow admins to view inactive products, but not regular users
        if (!$product->is_active && (!auth()->check() || auth()->user()->role !== 'admin')) {
            abort(404);
        }
        $product->load(['reviews.user', 'seller.sellerProfile', 'type']);
        return view('buyer.product_details', compact('product'));
    }

    public function orders()
    {
        $orders = \App\Models\Order::where('buyer_id', auth()->id())->with('product.seller.sellerProfile')->latest()->paginate(10);
        return view('buyer.orders.index', compact('orders'));
    }

    public function showOrder(\App\Models\Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }
        return view('buyer.orders.show', compact('order'));
    }
}
