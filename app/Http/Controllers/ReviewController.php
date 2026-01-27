<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function create(Order $order)
    {
        // specific validation to ensure user owns order and status is completed
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'You can only review completed orders.');
        }

        if ($order->review) {
            return redirect()->back()->with('error', 'You have already reviewed this item.');
        }

        return view('buyer.reviews.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        if ($order->review) {
            return redirect()->route('buyer.orders.index')->with('error', 'Already reviewed.');
        }

        // Debug logging
        \Log::info('Review submission data:', [
            'has_file' => $request->hasFile('image'),
            'all_data' => $request->all(),
            'files' => $request->allFiles(),
        ]);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $order->product_id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('buyer.orders.index')->with('success', 'Thank you for your review!');
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        // Load the order relation for the view context
        $order = $review->order;

        return view('buyer.reviews.edit', compact('review', 'order'));
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        // Debug logging
        \Log::info('Review UPDATE data:', [
            'review_id' => $review->id,
            'has_file' => $request->hasFile('image'),
            'all_data' => $request->all(),
            'files' => $request->allFiles(),
        ]);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'image' => 'nullable|image|max:10240', // 10MB max per .htaccess
        ]);

        $data = [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image_path) {
                Storage::disk('public')->delete($review->image_path);
            }
            $data['image_path'] = $request->file('image')->store('reviews', 'public');
        }

        $review->update($data);

        return redirect()->route('buyer.orders.index')->with('success', 'Review updated successfully!');
    }
}
