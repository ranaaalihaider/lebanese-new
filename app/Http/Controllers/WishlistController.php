<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlistProducts()->latest()->paginate(12);
        return view('buyer.wishlist', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $product_id = $request->product_id;
        $user_id = Auth::id();

        $deleted = Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->delete();

        if ($deleted) {
            return response()->json(['status' => 'removed', 'message' => 'Product removed from wishlist']);
        } else {
            Wishlist::create(['user_id' => $user_id, 'product_id' => $product_id]);
            return response()->json(['status' => 'added', 'message' => 'Product added to wishlist']);
        }
    }
}
