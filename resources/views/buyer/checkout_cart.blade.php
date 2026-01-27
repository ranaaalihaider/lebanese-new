@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('buyer.cart.index') }}"
            class="inline-flex items-center text-sm text-stone-500 hover:text-emerald-600 mb-6 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Cart
        </a>

        <h1 class="text-2xl font-bold mb-8 text-stone-900">Checkout</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="md:col-span-1 md:order-2">
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-stone-900 mb-4">Order Summary</h2>

                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cartItems as $item)
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-stone-100 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://placehold.co/100' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-stone-900 line-clamp-2">{{ $item->product->name }}</h3>
                                    <p class="text-xs text-stone-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="ml-auto">
                                    <span
                                        class="text-xs font-medium">${{ number_format($item->product->final_price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-stone-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-600">Delivery</span>
                            <span class="font-medium text-emerald-600">Pending</span>
                        </div>
                        <div class="border-t border-stone-100 pt-2 mt-2 flex justify-between items-center">
                            <span class="font-bold text-stone-900">Total</span>
                            <span class="font-bold text-xl text-emerald-700">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="md:col-span-2 md:order-1">
                <form action="{{ route('buyer.checkout.cart.store') }}" method="POST">
                    @csrf

                    <!-- Delivery Address -->
                    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 mb-6">
                        <h2 class="text-lg font-bold text-stone-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Delivery Address
                        </h2>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-stone-700 mb-2">Full Address</label>
                            <textarea name="delivery_address" rows="3"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                placeholder="Street, Building, Floor, Apartment..."
                                required>{{ old('delivery_address', implode(', ', array_filter([Auth::user()->house_no, Auth::user()->street, Auth::user()->city, Auth::user()->state, Auth::user()->postal_code]))) }}</textarea>
                            <p class="text-xs text-stone-400 mt-2">Please provide as much detail as possible for the
                                courier.</p>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 mb-8">
                        <h2 class="text-lg font-bold text-stone-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Payment Method
                        </h2>

                        <div class="space-y-3">
                            @php
                                $enabledMethods = [];
                                if (\App\Models\Setting::getSetting('payment_method_cod') == '1')
                                    $enabledMethods['COD'] = 'Cash on Delivery (COD)';
                                if (\App\Models\Setting::getSetting('payment_method_online') == '1')
                                    $enabledMethods['Online'] = 'Full Online Payment';
                                if (\App\Models\Setting::getSetting('payment_method_partial') == '1')
                                    $enabledMethods['Partial'] = 'Partial Online Payment';
                            @endphp

                            @if(count($enabledMethods) > 0)
                                @foreach($enabledMethods as $value => $label)
                                    <label
                                        class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-emerald-50/50 transition-colors {{ $loop->first ? 'border-emerald-500 bg-emerald-50/30' : 'border-stone-200' }}">
                                        <input type="radio" name="payment_method" value="{{ $value }}"
                                            class="text-emerald-600 focus:ring-emerald-500" {{ $loop->first ? 'checked' : '' }}
                                            required>
                                        <span class="ml-3 font-bold text-stone-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            @else
                                <div class="p-4 border border-red-200 bg-red-50 rounded-xl text-red-700 text-sm">
                                    No payment methods are currently available. Please contact support.
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-emerald-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl hover:bg-emerald-700 transition-all active:scale-95 text-lg">
                        Place Order for {{ $cartItems->count() }} Items
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection