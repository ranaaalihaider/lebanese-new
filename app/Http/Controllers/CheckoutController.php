<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Product $product)
    {
        return view('buyer.checkout', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $enabledMethods = [];
        if (\App\Models\Setting::getSetting('payment_method_cod') == '1')
            $enabledMethods[] = 'COD';
        if (\App\Models\Setting::getSetting('payment_method_online') == '1')
            $enabledMethods[] = 'Online';
        if (\App\Models\Setting::getSetting('payment_method_partial') == '1')
            $enabledMethods[] = 'Partial';

        $rules = 'required|in:' . implode(',', $enabledMethods);

        $request->validate([
            'delivery_address' => 'required|string|max:1000',
            'payment_method' => $rules,
        ]);

        $sellerEarning = $product->price;
        $platformFee = $product->final_price - $sellerEarning;

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'buyer_id' => Auth::id(),
            'seller_id' => $product->seller_id,
            'product_id' => $product->id,
            'quantity' => 1,
            'total_price' => $product->final_price,
            'platform_fee' => $platformFee,
            'seller_earning' => $sellerEarning,
            'delivery_address' => $request->delivery_address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        // Notify Seller
        $seller = \App\Models\User::find($product->seller_id);
        if ($seller) {
            $seller->notify(new \App\Notifications\NewOrderNotification($order));
        }

        return redirect()->route('buyer.orders.index')->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }

    public function showCart()
    {
        $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with('product.seller.sellerProfile')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->final_price * $item->quantity;
        });

        return view('buyer.checkout_cart', compact('cartItems', 'total'));
    }

    public function storeCart(Request $request)
    {
        $enabledMethods = [];
        if (\App\Models\Setting::getSetting('payment_method_cod') == '1')
            $enabledMethods[] = 'COD';
        if (\App\Models\Setting::getSetting('payment_method_online') == '1')
            $enabledMethods[] = 'Online';
        if (\App\Models\Setting::getSetting('payment_method_partial') == '1')
            $enabledMethods[] = 'Partial';

        $rules = 'required|in:' . implode(',', $enabledMethods);

        $request->validate([
            'delivery_address' => 'required|string|max:1000',
            'payment_method' => $rules,
        ]);

        $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cartItems as $item) {
            $product = $item->product;
            $sellerEarning = $product->price * $item->quantity;
            $platformFee = ($product->final_price * $item->quantity) - $sellerEarning;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => Auth::id(),
                'seller_id' => $product->seller_id,
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'total_price' => $product->final_price * $item->quantity,
                'platform_fee' => $platformFee,
                'seller_earning' => $sellerEarning,
                'delivery_address' => $request->delivery_address,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // Notify Seller
            $seller = \App\Models\User::find($product->seller_id);
            if ($seller) {
                $seller->notify(new \App\Notifications\NewOrderNotification($order));
            }
        }

        // Clear Cart
        \App\Models\Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('buyer.orders.index')->with('success', 'Orders placed successfully!');
    }
}
