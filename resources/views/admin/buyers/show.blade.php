@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-stone-50 to-stone-100 pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-stone-200 px-4 md:px-6 py-6 md:py-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-stone-900">{{ $buyer->name }}</h1>
                        <p class="text-stone-600 text-sm md:text-base mt-1">Buyer Profile & Order History</p>
                    </div>
                    <a href="{{ route('admin.buyers') }}"
                        class="bg-stone-200 hover:bg-stone-300 text-stone-700 px-4 py-2 rounded-xl font-bold transition-colors">
                        ← Back
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 md:px-6 md:py-8">
            <!-- Profile & Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Contact Information -->
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-stone-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Contact Information
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-stone-500 uppercase font-bold">Email</p>
                            <p class="text-sm text-stone-900">{{ $buyer->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500 uppercase font-bold">Phone</p>
                            <p class="text-sm text-stone-900">
                                <a href="tel:{{ $buyer->phone }}"
                                    class="text-blue-600 hover:underline">{{ $buyer->phone }}</a>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500 uppercase font-bold">Member Since</p>
                            <p class="text-sm text-stone-900">{{ $buyer->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Statistics -->
                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-2xl border border-blue-200">
                        <p class="text-xs font-bold text-blue-600 uppercase">Total Orders</p>
                        <p class="text-3xl font-bold text-blue-900 mt-1">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 rounded-2xl border border-emerald-200">
                        <p class="text-xs font-bold text-emerald-600 uppercase">Total Spent</p>
                        <p class="text-3xl font-bold text-emerald-900 mt-1">${{ number_format($stats['total_spent'], 2) }}
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-2xl border border-amber-200">
                        <p class="text-xs font-bold text-amber-600 uppercase">Pending</p>
                        <p class="text-3xl font-bold text-amber-900 mt-1">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-2xl border border-purple-200">
                        <p class="text-xs font-bold text-purple-600 uppercase">Completed</p>
                        <p class="text-3xl font-bold text-purple-900 mt-1">{{ $stats['completed_orders'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
                <h3 class="text-xl font-bold text-stone-900 mb-4">Order History</h3>

                @if($orders->count() > 0)
                    <!-- Mobile: Order Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach($orders as $order)
                            <div class="border border-stone-200 rounded-xl p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                            {{ $order->order_number }}
                                        </a>
                                        <p class="text-xs text-stone-500 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-lg text-xs font-bold
                                                @if($order->status === 'pending') bg-amber-100 text-amber-700
                                                @elseif($order->status === 'completed') bg-emerald-100 text-emerald-700
                                                @else bg-blue-100 text-blue-700
                                                @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <p class="text-sm font-medium text-stone-900 mb-1">{{ $order->product->name }}</p>
                                <p class="text-xs text-stone-600 mb-2">From: {{ $order->seller->sellerProfile->store_name }}</p>
                                <p class="text-lg font-bold text-emerald-600">${{ number_format($order->total_price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop: Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200">
                            <thead class="bg-stone-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase">Order #</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase">Seller</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-stone-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-200">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-stone-50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-stone-600 whitespace-nowrap">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-stone-700">{{ $order->product->name }}</td>
                                        <td class="px-4 py-3 text-sm text-stone-700">
                                            <a href="{{ route('admin.stores.show', $order->seller_id) }}"
                                                class="text-blue-600 hover:underline">
                                                {{ $order->seller->sellerProfile->store_name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-lg text-xs font-bold
                                                        @if($order->status === 'pending') bg-amber-100 text-amber-700
                                                        @elseif($order->status === 'completed') bg-emerald-100 text-emerald-700
                                                        @else bg-blue-100 text-blue-700
                                                        @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-bold text-emerald-600 whitespace-nowrap">
                                            ${{ number_format($order->total_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto text-stone-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-stone-500">No orders yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection