@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-stone-800 mb-6">My Orders</h1>

        @if($orders->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <li>
                            <div class="block hover:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-emerald-600 truncate">
                                            <a href="{{ route('buyer.orders.show', $order) }}" class="hover:underline">
                                                #{{ $order->order_number }}
                                            </a>
                                        </h3>
                                        <div class="ml-2 flex-shrink-0 flex gap-2">
                                            @if($order->status === 'completed')
                                                @if(!$order->review)
                                                    <a href="{{ route('buyer.reviews.create', $order) }}"
                                                        class="px-3 py-1 text-xs font-bold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 shadow-sm transition-colors">
                                                        Write Review
                                                    </a>
                                                @else
                                                    <a href="{{ route('buyer.reviews.edit', $order->review) }}"
                                                        class="px-3 py-1 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-full hover:bg-emerald-100 transition-colors">
                                                        Edit Review
                                                    </a>
                                                @endif
                                            @endif
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
                                            <span
                                                class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <span class="truncate">{{ $order->product->name }} (x{{ $order->quantity }})</span>
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <span class="truncate">from
                                                    {{ $order->product->seller->sellerProfile->store_name ?? $order->product->seller->name }}</span>
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <p>
                                                Total: ${{ number_format($order->total_price, 2) }}
                                            </p>
                                            <p class="ml-6">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 text-center">
                <p class="text-gray-500">You haven't placed any orders yet.</p>
                <a href="{{ route('buyer.stores') }}" class="mt-4 inline-block text-emerald-600 hover:text-emerald-500">Browse
                    Stores &rarr;</a>
            </div>
        @endif
    </div>
@endsection