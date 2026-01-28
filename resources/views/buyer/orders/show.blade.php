@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('buyer.orders.index') }}" class="text-emerald-600 hover:text-emerald-500 font-medium">&larr;
                Back to My Orders</a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        @trans('Order Details')
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Order #{{ $order->order_number }}
                    </p>
                </div>
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'accepted' => 'bg-blue-100 text-blue-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'handed_to_courier' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                    ];
                    $statusLabel = ucfirst(str_replace('_', ' ', $order->status));
                    $class = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $class }}">
                    {{ $statusLabel }}
                </span>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            @trans('Product')
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ route('buyer.products.show', $order->product) }}"
                                class="text-emerald-600 hover:underline">
                                {{ $order->product->name }}
                            </a>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            @trans('Store')
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ route('buyer.stores.show', $order->product->seller) }}"
                                class="text-emerald-600 hover:underline">
                                {{ $order->product->seller->sellerProfile->store_name ?? $order->product->seller->name }}
                            </a>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            @trans('Quantity')
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $order->quantity }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            @trans('Total Price')
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-bold">
                            ${{ number_format($order->total_price, 2) }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            @trans('Order Date')
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $order->created_at->format('F d, Y h:i A') }}
                        </dd>
                    </div>

                    @if($order->status == 'pending')
                        <div class="bg-white px-4 py-5 sm:px-6">
                            <div class="rounded-md bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <!-- Heroicon name: solid/information-circle -->
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1 md:flex md:justify-between">
                                        <p class="text-sm text-blue-700">
                                            @trans('This order is pending acceptance by the seller.')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($order->seller_notes)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                @trans('Seller Notes')
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $order->seller_notes }}
                            </dd>
                        </div>
                    @endif

                    <div class="bg-gray-100 px-4 py-5 sm:px-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">@trans('Delivery Information')</h4>
                        <p class="text-sm text-gray-600">{{ $order->customer_name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->customer_address }}</p>
                        <p class="text-sm text-gray-600">Phone: {{ $order->customer_phone }}</p>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection