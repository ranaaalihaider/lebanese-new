@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-stone-50 to-stone-100 pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-stone-200 px-4 md:px-6 py-6 md:py-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl md:text-3xl font-bold text-stone-900">@trans('Platform Revenue')</h1>
                <p class="text-stone-600 text-sm md:text-base mt-1">@trans('Track earnings and transaction history')</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 md:px-6 md:py-8">
            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-4 md:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-stone-900">@trans('Filters')</h2>
                    <button onclick="toggleRevenueFilters()"
                        class="px-4 py-2 bg-stone-100 text-stone-600 rounded-xl flex items-center gap-2 hover:bg-stone-200 transition-colors text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        @trans('Toggle Filters')
                    </button>
                </div>

                <div id="revenue-filter-container"
                    class="{{ request()->anyFilled(['search', 'store_id', 'date_from', 'date_to']) ? '' : 'hidden' }}">
                    <form method="GET" action="{{ route('admin.earnings') }}" class="space-y-4">
                        <!-- Search Bar -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                @trans('Search by Store, Order #, or Product')
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search transactions..."
                                class="w-full px-4 py-2.5 border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Store Filter -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-2">@trans('Filter by Store')</label>
                                <select name="store_id"
                                    class="w-full px-3 py-2.5 border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="all" {{ request('store_id') == 'all' || !request('store_id') ? 'selected' : '' }}>
                                        @trans('All Stores')</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" {{ request('store_id') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->sellerProfile->store_name ?? $seller->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-2">@trans('From Date')</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="w-full px-3 py-2.5 border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-2">@trans('To Date')</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="w-full px-3 py-2.5 border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold transition-colors">
                                    @trans('Apply')
                                </button>
                                <a href="{{ route('admin.earnings') }}"
                                    class="bg-stone-200 hover:bg-stone-300 text-stone-700 px-4 py-2.5 rounded-xl font-bold transition-colors">
                                    @trans('Clear')
                                </a>
                            </div>
                        </div>

                        @if(request('search') || request('store_id') || request('date_from') || request('date_to'))
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                                <p class="text-sm text-blue-800">
                                    <strong>@trans('Active Filters:')</strong>
                                    @if(request('search'))
                                        Search: <span class="font-bold">"{{ request('search') }}"</span>
                                    @endif
                                    @if(request('store_id') && request('store_id') !== 'all')
                                        | Store: <span
                                            class="font-bold">{{ $sellers->find(request('store_id'))->sellerProfile->store_name ?? 'Selected Store' }}</span>
                                    @endif
                                    @if(request('date_from'))
                                        | From: <span class="font-bold">{{ request('date_from') }}</span>
                                    @endif
                                    @if(request('date_to'))
                                        | To: <span class="font-bold">{{ request('date_to') }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

    <script>
        function toggleRevenueFilters() {
            document.getElementById('revenue-filter-container').classList.toggle('hidden');
        }
    </script>

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
                    <div
                        class="bg-gradient-to-br from-stone-50 to-stone-100 p-4 md:p-5 rounded-2xl border border-stone-200 shadow-sm">
                        <p class="text-xs font-bold text-stone-600 uppercase">@trans('Total Orders')</p>
                        <p class="text-2xl md:text-3xl font-bold text-stone-900 mt-1">{{ $totalOrders }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 md:p-5 rounded-2xl border border-purple-200 shadow-sm">
                        <p class="text-xs font-bold text-purple-600 uppercase">Volume (GMV)</p>
                        <p class="text-2xl md:text-3xl font-bold text-purple-900 mt-1">${{ number_format($totalVolume, 2) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 md:p-5 rounded-2xl border border-emerald-200 shadow-sm">
                        <p class="text-xs font-bold text-emerald-600 uppercase">@trans('Net Revenue')</p>
                        <p class="text-2xl md:text-3xl font-bold text-emerald-900 mt-1">
                            ${{ number_format($totalRevenue, 2) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 md:p-5 rounded-2xl border border-blue-200 shadow-sm">
                        <p class="text-xs font-bold text-blue-600 uppercase">@trans('Seller Earnings')</p>
                        <p class="text-2xl md:text-3xl font-bold text-blue-900 mt-1">
                            ${{ number_format($totalSellerEarnings, 2) }}</p>
                    </div>
                </div>

                @if($orders->count() > 0)
                    <!-- Mobile: Transaction Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach($orders as $order)
                            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                                <!-- Header -->
                                <div class="p-4 bg-gradient-to-r from-emerald-50 to-white border-b border-emerald-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="text-sm font-bold text-emerald-700 hover:text-emerald-800 hover:underline">
                                                {{ $order->order_number }}
                                            </a>
                                            <p class="text-xs text-stone-500 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="bg-emerald-500 text-white text-lg font-bold px-3 py-1.5 rounded-lg">
                                            +${{ number_format($order->platform_fee, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="p-4 space-y-3">
                                    <!-- Product -->
                                    <div>
                                        <p class="text-xs text-stone-500 uppercase font-bold mb-1">@trans('Product')</p>
                                        <p class="font-medium text-stone-900 text-sm">{{ $order->product->name }}</p>
                                        <p class="text-xs text-stone-500">Qty: {{ $order->quantity }}</p>
                                    </div>

                                    <!-- Seller -->
                                    <div>
                                        <p class="text-xs text-stone-500 uppercase font-bold mb-1">@trans('Seller')</p>
                                        <a href="{{ route('admin.stores.show', $order->seller_id) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                                            {{ $order->seller->sellerProfile->store_name }}
                                        </a>
                                    </div>

                                    <!-- Financials -->
                                    <div class="bg-stone-50 rounded-xl p-3 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-stone-600">@trans('Order Total')</span>
                                            <span
                                                class="font-bold text-stone-900">${{ number_format($order->total_price, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-stone-600">@trans('Seller Cut')</span>
                                            <span class="text-stone-700">${{ number_format($order->seller_earning, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm pt-2 border-t border-stone-200">
                                            <span class="font-bold text-emerald-700">Platform Fee (6%)</span>
                                            <span
                                                class="font-bold text-emerald-600">${{ number_format($order->platform_fee, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop: Table -->
                    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-stone-200">
                                <thead class="bg-stone-50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Date')</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Order #')</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Product')</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Seller')</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Total')</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Seller Cut')</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                            @trans('Platform Fee')</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-stone-200">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-stone-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-600">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="max-w-xs">
                                                    <p class="font-medium text-stone-900 truncate">{{ $order->product->name }}</p>
                                                    <p class="text-xs text-stone-500">Qty: {{ $order->quantity }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('admin.stores.show', $order->seller_id) }}"
                                                    class="text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                                                    {{ $order->seller->sellerProfile->store_name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-stone-900">
                                                ${{ number_format($order->total_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-stone-600">
                                                ${{ number_format($order->seller_earning, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200">
                                                    <span
                                                        class="text-sm font-bold text-emerald-700">+${{ number_format($order->platform_fee, 2) }}</span>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-stone-200">
                        <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-stone-900">@trans('No revenue data found')</h3>
                        <p class="text-stone-500 mt-1">@trans('Try adjusting your filters or wait for sellers to complete orders.')</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Bottom Navigation -->
        @include('components.bottom-nav')
@endsection