@extends('layouts.app')

@section('content')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $seller->sellerProfile->store_name }}</h1>
            <p class="mt-2 text-gray-600">{{ $seller->sellerProfile->pickup_location }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <a href="{{ route('buyer.products.show', $product) }}" class="group block">
                    <div class="w-full aspect-square bg-gray-200 rounded-lg overflow-hidden mb-2">
                        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/300' }}"
                            class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <h3 class="mt-1 text-sm text-gray-700 font-medium truncate">{{ $product->name }}</h3>
                    <p class="mt-1 text-lg font-bold text-emerald-700">${{ number_format($product->final_price, 2) }}</p>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500 py-10">No products found in this store.</p>
            @endforelse
        </div>
    </div>
@endsection