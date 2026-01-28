<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function create(Request $request)
    {
        $sellerId = $request->query('seller_id');

        if (!$sellerId) {
            return redirect()->route('admin.sellers')->with('error', 'Seller ID is required.');
        }

        $seller = User::findOrFail($sellerId);

        if ($seller->role !== 'seller') {
            return redirect()->route('admin.sellers')->with('error', 'Invalid seller.');
        }

        $types = ProductType::all();
        return view('admin.products.create', compact('types', 'seller'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['photos']);
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

        return redirect()->route('admin.stores.show', $request->seller_id)
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $types = ProductType::all();
        $photos = $product->photos;
        $seller = $product->seller;

        return view('admin.products.edit', compact('product', 'types', 'photos', 'seller'));
    }

    public function update(Request $request, Product $product)
    {
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

        return redirect()->route('admin.stores.show', $product->seller_id)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $sellerId = $product->seller_id;

        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('admin.stores.show', $sellerId)
            ->with('success', 'Product deleted successfully.');
    }
}
