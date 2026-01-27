@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-10">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-bold text-stone-900">Edit Profile</h1>
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">Back to
                    Dashboard</a>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Public Info -->
                <div class="mb-8 border-b border-stone-100 pb-8">
                    <h2 class="text-lg font-semibold text-stone-800 mb-4">Account Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Address Info (Buyers Only) -->
                @if($user->role !== 'seller')
                    <div class="mb-8 border-b border-stone-100 pb-8">
                        <h2 class="text-lg font-semibold text-stone-800 mb-4">Delivery Address</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">House No / Building</label>
                                <input type="text" name="house_no" value="{{ old('house_no', $user->house_no) }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">Street</label>
                                <input type="text" name="street" value="{{ old('street', $user->street) }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">City</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">State / District</label>
                                <input type="text" name="state" value="{{ old('state', $user->state) }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->role === 'seller')
                    <!-- Seller Pickup Location -->
                    <div class="mb-8 border-b border-stone-100 pb-8">
                        <h2 class="text-lg font-semibold text-stone-800 mb-4">Store Pickup Location</h2>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-stone-600 mb-2">Full Pickup Address</label>
                            <input type="text" name="pickup_location"
                                value="{{ old('pickup_location', $user->sellerProfile->pickup_location ?? '') }}"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                placeholder="e.g. Shop 4, Market Street, Beirut">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">Latitude</label>
                                <input type="text" name="latitude" id="latitude"
                                    value="{{ old('latitude', $user->sellerProfile->latitude ?? '') }}"
                                    class="w-full rounded-xl border-stone-200 bg-stone-50 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">Longitude</label>
                                <input type="text" name="longitude" id="longitude"
                                    value="{{ old('longitude', $user->sellerProfile->longitude ?? '') }}"
                                    class="w-full rounded-xl border-stone-200 bg-stone-50 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    readonly>
                            </div>
                        </div>

                        <div>
                            <button type="button" onclick="getCurrentLocation()"
                                class="flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors text-sm font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Get Current Location
                            </button>
                            <p id="location-status" class="text-xs text-stone-500 mt-2 ml-1"></p>
                        </div>

                        <script>
                            function getCurrentLocation() {
                                const status = document.getElementById('location-status');
                                if (!navigator.geolocation) {
                                    status.textContent = 'Geolocation is not supported by your browser';
                                    return;
                                }

                                status.textContent = 'Locating...';

                                navigator.geolocation.getCurrentPosition(
                                    (position) => {
                                        document.getElementById('latitude').value = position.coords.latitude;
                                        document.getElementById('longitude').value = position.coords.longitude;
                                        status.textContent = 'Location found!';
                                        status.className = 'text-xs text-green-600 mt-2 ml-1 font-bold';
                                    },
                                    () => {
                                        status.textContent = 'Unable to retrieve your location';
                                        status.className = 'text-xs text-red-500 mt-2 ml-1';
                                    }
                                );
                            }
                        </script>
                    </div>

                    <!-- Payment Details (Seller Only) -->
                    <div class="mb-8 border-b border-stone-100 pb-8">
                        <h2 class="text-lg font-semibold text-stone-800 mb-4">Payout Details</h2>
                        <p class="text-sm text-stone-500 mb-4">Enter your bank details to receive payments from the admin.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">Bank Name</label>
                                <input type="text" name="bank_name"
                                    value="{{ old('bank_name', $user->sellerProfile->bank_name ?? '') }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="e.g. BLOM Bank">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-stone-600 mb-2">Account Title</label>
                                <input type="text" name="account_title"
                                    value="{{ old('account_title', $user->sellerProfile->account_title ?? '') }}"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="e.g. John Doe">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-2">Account Number / IBAN</label>
                            <input type="text" name="account_number"
                                value="{{ old('account_number', $user->sellerProfile->account_number ?? '') }}"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                placeholder="e.g. LB12 3456 ...">
                        </div>
                    </div>
                @endif

                <!-- Password -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-stone-800 mb-4">Security</h2>
                    <div class="space-y-4 max-w-md">
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-2">Current Password (optional)</label>
                            <input type="password" name="current_password"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                placeholder="Only if changing password">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-2">New Password</label>
                            <input type="password" name="new_password"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-2">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection