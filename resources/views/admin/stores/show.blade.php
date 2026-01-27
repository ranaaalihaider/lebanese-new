@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('admin.sellers') }}"
            class="inline-flex items-center text-sm text-stone-500 hover:text-emerald-600 mb-6 transition-colors font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Sellers
        </a>

        <!-- Store Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-8 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-stone-900">{{ $seller->sellerProfile->store_name ?? $seller->name }}
                    </h1>
                    @if($seller->sellerProfile->store_tagline)
                        <p class="text-stone-500 mt-1">{{ $seller->sellerProfile->store_tagline }}</p>
                    @endif
                    <div class="flex gap-4 text-sm text-stone-500 mt-2">
                        <p>Owner: <span class="font-bold text-stone-700">{{ $seller->name }}</span></p>
                        <p>•</p>
                        <p>Phone: <a href="tel:{{ $seller->phone }}"
                                class="text-emerald-600 hover:underline font-bold">{{ $seller->phone }}</a></p>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('admin.sellers.edit', $seller->id) }}"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold transition-colors shadow-sm">
                    Edit Seller Account
                </a>
            </div>
        </div>

        <!-- Analytics Section (Collapsible) -->
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 mb-6" x-data="{ open: false }">
            <button @click="open = !open"
                class="w-full p-6 flex justify-between items-center hover:bg-stone-50 transition-colors rounded-2xl">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <h2 class="text-xl font-bold text-stone-900">Store Analytics</h2>
                </div>
                <svg x-show="!open" class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <svg x-show="open" class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>

            <div x-show="open" x-collapse style="display: none;">
                <div class="px-6 pb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Total Products -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total Products</p>
                                <p class="text-3xl font-bold text-blue-900 mt-1">{{ $totalProducts }}</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Completed Orders -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Completed Orders</p>
                                <p class="text-3xl font-bold text-green-900 mt-1">{{ $completedOrders }}</p>
                            </div>
                            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Pending Orders -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-5 rounded-xl border border-yellow-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-yellow-600 uppercase tracking-wide">Pending Orders</p>
                                <p class="text-3xl font-bold text-yellow-900 mt-1">{{ $pendingOrders }}</p>
                            </div>
                            <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-5 rounded-xl border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Total Sales</p>
                                <p class="text-3xl font-bold text-purple-900 mt-1">${{ number_format($totalSales, 2) }}</p>
                            </div>
                            <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Platform Revenue -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 rounded-xl border border-emerald-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Platform Revenue</p>
                                <p class="text-3xl font-bold text-emerald-900 mt-1">${{ number_format($totalRevenue, 2) }}
                                </p>
                            </div>
                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Seller Earnings -->
                    <div class="bg-gradient-to-br from-stone-50 to-stone-100 p-5 rounded-xl border border-stone-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-stone-600 uppercase tracking-wide">Seller Earnings</p>
                                <p class="text-3xl font-bold text-stone-900 mt-1">
                                    ${{ number_format($totalSales - $totalRevenue, 2) }}</p>
                            </div>
                            <svg class="w-10 h-10 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-8">
            <h2 class="text-xl font-bold text-stone-900 mb-6">Store Products ({{ $totalProducts }})</h2>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div
                            class="bg-stone-50 rounded-xl border border-stone-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="aspect-square bg-stone-200 relative">
                                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/300' }}"
                                    alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-stone-900 mb-1">{{ $product->name }}</h3>
                                <p class="text-xs text-stone-500 mb-2">{{ $product->type->name ?? 'Uncategorized' }}</p>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-lg font-bold text-emerald-600">${{ number_format($product->final_price, 2) }}</span>
                                    <span class="text-xs text-stone-400">Base: ${{ number_format($product->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-stone-500">No products listed yet</p>
                </div>
            @endif
        </div>
    </div>
@endsection