@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if($cartItems->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="flex-1 bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <li class="flex py-6 px-6">
                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                @if($item->product->thumbnail)
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover object-center">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-gray-100 text-2xl">📦</div>
                                @endif
                            </div>

                            <div class="ml-4 flex flex-1 flex-col">
                                <div>
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3>
                                            <a href="{{ route('buyer.products.show', $item->product) }}">{{ $item->product->name }}</a>
                                        </h3>
                                        <p class="ml-4">{{ number_format($item->product->final_price * $item->quantity, 2) }} LBP</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $item->product->seller->sellerProfile->store_name ?? $item->product->seller->name }}</p>
                                </div>
                                <div class="flex flex-1 items-end justify-between text-sm">
                                    <p class="text-gray-500">Qty {{ $item->quantity }}</p>

                                    <form action="{{ route('buyer.cart.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-500">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-80">
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                    <div class="flow-root">
                        <dl class="-my-4 divide-y divide-gray-200">
                            <div class="flex items-center justify-between py-4">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ number_format($cartItems->sum(fn($i) => $i->product->final_price * $i->quantity), 2) }} LBP</dd>
                            </div>
                            <div class="flex items-center justify-between py-4">
                                <dt class="text-sm text-gray-600">Shipping</dt>
                                <dd class="text-sm font-medium text-gray-900">Calculated at checkout</dd>
                            </div>
                            <div class="flex items-center justify-between py-4">
                                <dt class="text-base font-medium text-gray-900">Order Total</dt>
                                <dd class="text-base font-medium text-gray-900">{{ number_format($cartItems->sum(fn($i) => $i->product->final_price * $i->quantity), 2) }} LBP</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('buyer.checkout.cart') }}" class="block w-full rounded-md border border-transparent bg-emerald-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-center">Checkout</a>
                    </div>
                    <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                        <p>
                            or
                            <a href="{{ route('buyer.home') }}" class="font-medium text-emerald-600 hover:text-emerald-500">
                                Continue Shopping
                                <span aria-hidden="true"> &rarr;</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
            <p class="mt-1 text-sm text-gray-500">Start adding products to see them here.</p>
            <div class="mt-6">
                <a href="{{ route('buyer.home') }}" class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Browse Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
