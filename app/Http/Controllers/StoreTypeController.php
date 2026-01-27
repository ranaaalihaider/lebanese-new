<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreType;

class StoreTypeController extends Controller
{
    public function index()
    {
        $types = StoreType::all();
        return view('admin.store_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        StoreType::create($request->all());
        return back()->with('success', 'Store Type added.');
    }

    public function update(Request $request, StoreType $storeType)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $storeType->update($request->all());
        return back()->with('success', 'Store Type updated successfully.');
    }

    public function destroy(StoreType $storeType)
    {
        // Check if store type has associated seller profiles
        if ($storeType->sellerProfiles()->count() > 0) {
            return back()->with('error', 'Cannot delete this store category because it has ' . $storeType->sellerProfiles()->count() . ' associated store(s). Please reassign or delete the stores first.');
        }

        $storeType->delete();
        return back()->with('success', 'Store Type deleted.');
    }
}
