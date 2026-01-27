<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'house_no' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            // Add password validation if changing it
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->house_no = $request->house_no;
        $user->street = $request->street;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->postal_code = $request->postal_code;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Update Seller Profile if user is a seller
        if ($user->role === 'seller') {
            $request->validate([
                'pickup_location' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:255',
                'account_title' => 'nullable|string|max:255',
            ]);

            $user->sellerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'pickup_location' => $request->pickup_location,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_title' => $request->account_title,
                ]
            );
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
