<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $query = Product::where('seller_id', $sellerId)->with('type');

        if ($request->filled('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Filter by Category
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('product_type_id', $request->category_id);
        }

        $products = $query->latest()->get();

        // Stats calculation
        $totalProducts = Product::where('seller_id', $sellerId)->count();
        $activeProducts = Product::where('seller_id', $sellerId)->where('is_active', true)->count();
        $inactiveProducts = Product::where('seller_id', $sellerId)->where('is_active', false)->count();

        // Get categories for filter
        $types = ProductType::all();

        return view('seller.products.index', compact('products', 'totalProducts', 'activeProducts', 'inactiveProducts', 'types'));
    }

    public function create()
    {
        $types = ProductType::all();
        return view('seller.products.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['photos']);
        $data['seller_id'] = Auth::id();

        $product = Product::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('products', 'public');
                ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo_path' => $path,
                ]);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }
        $types = ProductType::all();
        $photos = $product->photos;
        return view('seller.products.edit', compact('product', 'types', 'photos'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $product->update($request->except(['photos']));

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('products', 'public');
                ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo_path' => $path,
                ]);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        // Also delete the legacy photo if it exists (optional, but good for cleanup)
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();
        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully.');
    }
    public function toggleStatus(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Product status updated successfully.');
    }
}
