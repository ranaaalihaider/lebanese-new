<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function index()
    {
        $types = ProductType::all();
        return view('admin.product_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        ProductType::create($request->all());
        return back()->with('success', 'Product Type added.');
    }

    public function update(Request $request, ProductType $productType)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $productType->update($request->all());
        return back()->with('success', 'Product Type updated successfully.');
    }

    public function destroy(ProductType $productType)
    {
        // Check if product type has associated products
        if ($productType->products()->count() > 0) {
            return back()->with('error', 'Cannot delete this product category because it has ' . $productType->products()->count() . ' associated product(s). Please reassign or delete the products first.');
        }

        $productType->delete();
        return back()->with('success', 'Product Type deleted.');
    }
}
