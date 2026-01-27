@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center text-sm text-stone-500 hover:text-emerald-600 mb-6 transition-colors font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to List
        </a>

        <!-- Order Header -->
        <div
            class="bg-white rounded-t-2xl shadow-sm border border-stone-100 p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-bold text-stone-900">Order #{{ $order->order_number }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'handed_to_courier' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
                <div class="flex gap-4 text-sm text-stone-500">
                    <p>Placed: {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p>•</p>
                    <p>Buyer: <span class="font-bold text-stone-700">{{ $order->buyer->name }}</span>
                        ({{ $order->buyer->phone }})</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                @if($order->status === 'pending')
                    <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit"
                            class="text-green-600 hover:text-green-900 bg-green-50 px-4 py-2 rounded-lg border border-green-200 hover:bg-green-100 font-bold transition-colors">Accept
                            Order</button>
                    </form>
                    <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit"
                            class="text-red-600 hover:text-red-900 bg-red-50 px-4 py-2 rounded-lg border border-red-200 hover:bg-red-100 font-bold transition-colors">Reject</button>
                    </form>
                @endif

                @if($order->status === 'accepted')
                    <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="handed_to_courier">
                        <button type="submit"
                            class="text-purple-600 hover:text-purple-900 bg-purple-50 px-4 py-2 rounded-lg border border-purple-200 hover:bg-purple-100 font-bold transition-colors">Hand
                            to Courier</button>
                    </form>
                @endif

                @if($order->status === 'handed_to_courier')
                    <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit"
                            class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-4 py-2 rounded-lg border border-emerald-200 hover:bg-emerald-100 font-bold transition-colors">Mark
                            Completed</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Order Details Grid -->
        <div class="bg-stone-50 border-x border-b border-stone-100 p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Order Logistics (Pickup & Delivery) -->
            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pickup Details (Seller/Store) -->
                <div>
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Pickup From
                    </h3>
                    <div class="bg-emerald-50 p-4 rounded-xl shadow-sm border border-emerald-100">
                        <p class="font-bold text-stone-900">
                            {{ $order->seller->sellerProfile->store_name ?? $order->seller->name }}</p>
                        <p class="text-sm text-stone-700 mt-1">
                            {{ $order->seller->sellerProfile->pickup_location ?? 'No pickup address set' }}
                        </p>
                        @if($order->seller->sellerProfile->latitude && $order->seller->sellerProfile->longitude)
                            <a href="https://maps.google.com/?q={{ $order->seller->sellerProfile->latitude }},{{ $order->seller->sellerProfile->longitude }}"
                                target="_blank"
                                class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 mt-2 hover:underline">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                View on Map
                            </a>
                        @endif
                        <div class="mt-3 pt-3 border-t border-emerald-200/50">
                            <p class="text-sm text-stone-600">Store Phone: <a href="tel:{{ $order->seller->phone }}"
                                    class="text-emerald-700 font-bold hover:underline">{{ $order->seller->phone }}</a></p>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details (Buyer) -->
                <div>
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Deliver To
                    </h3>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-stone-200">
                        <p class="font-bold text-stone-900">{{ $order->buyer->name }}</p>
                        <p class="text-stone-700 leading-relaxed text-sm mt-1">{{ $order->delivery_address }}</p>
                        <div class="mt-3 pt-3 border-t border-stone-100">
                            <p class="text-sm text-stone-500">Buyer Phone: <a href="tel:{{ $order->buyer->phone }}"
                                    class="text-emerald-600 font-bold hover:underline">{{ $order->buyer->phone }}</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financials -->
            <div>
                <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Financial Breakdown
                </h3>
                <div class="bg-white p-4 rounded-xl shadow-xs border border-stone-200 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-500">Order Total (from Buyer)</span>
                        <span class="font-bold text-stone-900">${{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-500">Platform Fee (6%)</span>
                        <span class="font-bold text-red-500">-${{ number_format($order->platform_fee, 2) }}</span>
                    </div>
                    <div class="border-t border-stone-100 pt-2 flex justify-between text-sm">
                        <span class="font-bold text-emerald-700">Your Net Earning</span>
                        <span
                            class="font-bold text-emerald-700 text-lg">${{ number_format($order->seller_earning, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Item -->
        <div class="bg-white rounded-b-2xl shadow-sm border border-t-0 border-stone-100 p-6 md:p-8">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">Item Details</h3>
            <div class="flex gap-4 md:gap-6 items-center">
                <div
                    class="w-20 h-20 md:w-24 md:h-24 bg-stone-100 rounded-xl overflow-hidden flex-shrink-0 border border-stone-200 shadow-sm">
                    <img src="{{ $order->product->thumbnail ? asset('storage/' . $order->product->thumbnail) : 'https://placehold.co/100' }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-stone-900">{{ $order->product->name }}</h4>
                    <div class="flex items-center gap-4 text-sm mt-1">
                        <span class="text-stone-500">Quantity: <strong
                                class="text-stone-800">{{ $order->quantity }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->review)
        <!-- Customer Review -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-8">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Customer Review
            </h3>
            <div class="bg-stone-50 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $order->review->rating)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-stone-300 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="font-bold text-stone-900">{{ $order->review->rating }}.0</span>
                    <span class="text-stone-400 text-sm">• {{ $order->review->created_at->format('M d, Y') }}</span>
                </div>

                <p class="text-stone-700 leading-relaxed mb-4">{{ $order->review->comment }}</p>

                @if($order->review->image_path)
                    <div class="mt-3">
                        <p class="text-xs text-stone-500 mb-2 font-bold uppercase">Attached Image:</p>
                        <a href="{{ asset('storage/' . $order->review->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->review->image_path) }}"
                                class="h-32 w-auto rounded-lg border border-stone-200 hover:opacity-90 transition-opacity">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection