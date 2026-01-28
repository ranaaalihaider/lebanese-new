<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SellerProfile;
use App\Models\ProductType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'User not found. Please register.']);
        }

        $this->generateAndSendOtp($user);

        return redirect()->route('verify', ['phone' => $user->phone]);
    }

    public function showRegisterSeller()
    {
        $storeTypes = \App\Models\StoreType::all();
        return view('auth.register_seller', compact('storeTypes'));
    }

    public function registerSeller(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'store_name' => 'required|string|max:255',
            'store_tagline' => 'nullable|string|max:255',
            'store_type_id' => 'required',
            'custom_store_type' => 'nullable|string|max:255|required_if:store_type_id,other',
            'pickup_location' => 'required|string|max:255',
            'language_preference' => 'required|in:EN,AR,HY,FR',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $storeTypeId = $request->store_type_id;

        if ($storeTypeId === 'other') {
            $newType = \App\Models\StoreType::firstOrCreate(['name' => $request->custom_store_type]);
            $storeTypeId = $newType->id;
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => 'seller',
            'status' => 'pending',
        ]);

        SellerProfile::create([
            'user_id' => $user->id,
            'store_type_id' => $storeTypeId,
            'store_name' => $request->store_name,
            'store_tagline' => $request->store_tagline,
            'pickup_location' => $request->pickup_location,
            'language_preference' => $request->language_preference,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewSellerRegisteredNotification($user));
        }

        $this->generateAndSendOtp($user);

        return redirect()->route('verify', ['phone' => $user->phone]);
    }

    public function showRegisterBuyer()
    {
        return view('auth.register_buyer');
    }

    public function registerBuyer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => 'buyer',
            'status' => 'active',
        ]);

        $this->generateAndSendOtp($user);

        return redirect()->route('verify', ['phone' => $user->phone]);
    }

    public function showVerify(Request $request)
    {
        $phone = $request->query('phone');
        return view('auth.verify', compact('phone'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || $user->otp_code !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Invalid OTP']);
        }

        // Ideally check expiry here
        // if ($user->otp_expires_at < now()) ...

        // Clear OTP
        $user->update(['otp_code' => null, 'otp_expires_at' => null]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    private function generateAndSendOtp($user)
    {
        $otp = rand(1000, 9999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Placeholder: Flash it
        session()->flash('otp_message', "Your OTP code is: $otp");
    }
}
