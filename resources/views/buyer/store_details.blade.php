@extends('layouts.app')

@section('content')
    @php
        $allReviews = $products->pluck('reviews')->flatten();
        $avgRating = $allReviews->avg('rating');
        $reviewCount = $allReviews->count();
        
        $deliverySuccess = 0;
        if($reviewCount > 0){
            $onTimeCount = $allReviews->where('is_delivery_on_time', 1)->count();
            $deliverySuccess = round(($onTimeCount / $reviewCount) * 100);
        }
        
        $photos = $allReviews->whereNotNull('image_path')->pluck('image_path')->take(6);
    @endphp

    <!-- Store Header -->
    <div class="bg-white shadow-sm border-b border-stone-100">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <!-- Profile Image & Info -->
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl shadow-inner">
                        {{ substr($seller->sellerProfile->store_name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-stone-900 tracking-tight">{{ $seller->sellerProfile->store_name }}</h1>
                        <p class="mt-1 text-stone-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $seller->sellerProfile->pickup_location }}
                        </p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="flex-1 w-full md:w-auto grid grid-cols-3 gap-4">
                    <!-- Rating -->
                    <div class="bg-stone-50 p-4 rounded-xl text-center border border-stone-100">
                        <div class="text-2xl font-bold text-stone-900 flex items-center justify-center gap-1">
                            {{ number_format($avgRating, 1) }} <span class="text-yellow-400 text-lg">★</span>
                        </div>
                        <div class="text-xs font-semibold text-stone-500 uppercase tracking-wide mt-1">@trans('Rating')</div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="bg-stone-50 p-4 rounded-xl text-center border border-stone-100">
                        <div class="text-2xl font-bold text-stone-900">{{ $reviewCount }}</div>
                        <div class="text-xs font-semibold text-stone-500 uppercase tracking-wide mt-1">@trans('Reviews')</div>
                    </div>

                    <!-- Delivery Success -->
                    <div class="bg-stone-50 p-4 rounded-xl text-center border border-stone-100">
                        <div class="text-2xl font-bold text-emerald-600">{{ $deliverySuccess }}%</div>
                        <div class="text-xs font-semibold text-stone-500 uppercase tracking-wide mt-1">@trans('On Time')</div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery (If exists) -->
            @if($photos->count() > 0)
                <div class="mt-8 pt-6 border-t border-stone-100">
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wide mb-3">@trans('Customer Photos')</h3>
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach($photos as $photo)
                            <div class="w-20 h-20 flex-shrink-0 cursor-pointer hover:opacity-90 transition-opacity">
                                <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover rounded-lg shadow-sm border border-stone-200">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 px-4">
        <h2 class="text-xl font-bold text-stone-900 mb-6 px-1">@trans('Store Products')</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <a href="{{ route('buyer.products.show', $product) }}" class="group block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-stone-100">
                    <div class="w-full aspect-square bg-stone-100 relative">
                        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/300' }}"
                            class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500">
                        @if(!$product->is_active)
                             <div class="absolute inset-0 bg-stone-900/50 flex items-center justify-center text-white font-bold text-sm uppercase">@trans('Inactive')</div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm text-stone-700 font-medium truncate">{{ $product->name }}</h3>
                        <p class="mt-1 text-lg font-bold text-emerald-700">${{ number_format($product->final_price, 2) }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-dashed border-stone-200">
                    <div class="text-4xl mb-4">🏪</div>
                    <h3 class="text-lg font-medium text-stone-900">@trans('No products found')</h3>
                    <p class="text-stone-500 mt-1">@trans('This store has no active listings yet.')</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection