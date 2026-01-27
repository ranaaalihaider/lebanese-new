@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-4 md:py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header with collapsible filter toggle -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-stone-900">All Orders</h1>
                <button onclick="toggleFilters()"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-xl flex items-center gap-2 hover:bg-emerald-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Filters
                </button>
            </div>

            <!-- Filters (collapsible) -->
            <div id="filters-container" class="{{ request()->anyFilled(['order_number', 'status', 'store_id', 'buyer_id', 'start_date', 'end_date']) ? '' : 'hidden' }}">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-stone-100 mb-6">
                    <form action="{{ route('admin.orders.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="lg:col-span-1">
                                <label for="order_number" class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Search</label>
                                <input type="text" name="order_number" value="{{ request('order_number') }}"
                                    placeholder="Order ID..."
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Status</label>
                                <select name="status"
                                    class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="handed_to_courier" {{ request('status') == 'handed_to_courier' ? 'selected' : '' }}>With Courier</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Store -->
                            <div>
                                <label for="store_id" class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Store</label>
                                <select name="store_id" id="store-select"
                                    class="searchable-select w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                                    <option value="all">All Stores</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" {{ request('store_id') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->sellerProfile->store_name ?? $seller->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Buyer -->
                            <div>
                                <label for="buyer_id" class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Customer</label>
                                <select name="buyer_id" id="buyer-select"
                                    class="searchable-select w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                                    <option value="all">All Customers</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" {{ request('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                            {{ $buyer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Date Range</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                        class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm text-stone-600"
                                        placeholder="Start Date">
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                        class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm text-stone-600"
                                        placeholder="End Date">
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="lg:col-span-2 flex items-end gap-2">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Apply Filters
                                </button>
                                @if(request()->anyFilled(['order_number', 'status', 'store_id', 'buyer_id', 'start_date', 'end_date']) && (request('status') != 'all' || request('store_id') != 'all' || request('buyer_id') != 'all'))
                                    <a href="{{ route('admin.orders.index') }}" 
                                       class="px-4 py-2 bg-stone-100 text-stone-600 font-bold rounded-xl hover:bg-stone-200 transition-colors shadow-sm text-center">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                border: 1px solid #e7e5e4;
                border-radius: 0.75rem;
                height: 38px;
                padding: 4px 8px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 28px;
                padding-left: 0;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }

            .select2-dropdown {
                border: 1px solid #e7e5e4;
                border-radius: 0.75rem;
            }
        </style>

        <!-- Mobile Cards View (visible on small screens) -->
        <div class="md:hidden space-y-4 mb-4">
            @forelse($orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}"
                    class="block bg-white rounded-2xl shadow-sm border border-stone-100 p-4 active:bg-stone-50 transition-colors">
                    <!-- Header: Order # and Status -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-emerald-600">#{{ $order->order_number }}</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium uppercase tracking-wide
                                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $order->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $order->status === 'handed_to_courier' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $order->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>

                    <!-- Buyer Info -->
                    <div class="flex items-center gap-3 mb-3 pb-3 border-b border-stone-100">
                        <div
                            class="w-10 h-10 rounded-full bg-stone-100 flex items-center justify-center text-sm font-bold text-stone-500">
                            {{ substr($order->buyer->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-stone-500 uppercase tracking-wide">Buyer</p>
                            <p class="text-sm font-medium text-stone-900">{{ $order->buyer->name }}</p>
                        </div>
                    </div>

                    <!-- Store and Total -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-stone-500 uppercase tracking-wide mb-1">Store</p>
                            <p class="text-sm font-medium text-stone-900">
                                {{ $order->seller->sellerProfile->store_name ?? $order->seller->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500 uppercase tracking-wide mb-1">Price Details</p>
                            <div class="text-xs text-stone-500 flex justify-between"><span>Base:</span>
                                <span>${{ number_format($order->product->price * $order->quantity, 2) }}</span>
                            </div>
                            <div class="text-xs text-stone-400 flex justify-between"><span>Fee:</span>
                                <span>${{ number_format($order->platform_fee, 2) }}</span>
                            </div>
                            <div
                                class="text-sm font-bold text-emerald-600 flex justify-between border-t border-stone-100 mt-1 pt-1">
                                <span>Final:</span> <span>${{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="mt-3 pt-3 border-t border-stone-100">
                        <p class="text-xs text-stone-400">{{ $order->created_at->format('M d, Y • H:i') }}</p>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-12 text-center">
                    <svg class="w-16 h-16 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <p class="text-stone-500">No orders found matching your criteria.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View (hidden on small screens) -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Order
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Buyer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Store
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Bill
                                Total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Placed
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse($orders as $order)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="group flex flex-col">
                                        <span
                                            class="font-bold text-emerald-600 group-hover:text-emerald-700 transition-colors">#{{ $order->order_number }}</span>
                                        <span class="text-xs text-stone-400">View Details</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.buyers.show', $order->buyer->id) }}"
                                        class="flex items-center gap-3 group">
                                        <div
                                            class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-xs font-bold text-stone-500">
                                            {{ substr($order->buyer->name, 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-stone-900 group-hover:text-emerald-600 transition-colors">{{ $order->buyer->name }}</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.stores.show', $order->seller_id) }}"
                                        class="text-sm font-medium text-stone-900 hover:text-emerald-600 transition-colors">
                                        {{ $order->seller->sellerProfile->store_name ?? $order->seller->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-emerald-600">${{ number_format($order->total_price, 2) }}</span>
                                        <span class="text-xs text-stone-500">Base:
                                            ${{ number_format($order->product->price * $order->quantity, 2) }}</span>
                                        <span class="text-xs text-stone-400">Fee:
                                            ${{ number_format($order->platform_fee, 2) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase tracking-wide
                                                                                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                                                    {{ $order->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                                                    {{ $order->status === 'handed_to_courier' ? 'bg-purple-100 text-purple-800' : '' }}
                                                                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                                                                    {{ $order->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-500">
                                    {{ $order->created_at->format('M d, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-stone-500 text-sm">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-stone-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <p>No orders found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        <!-- Mobile Pagination -->
        @if($orders->hasPages())
            <div class="md:hidden mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.searchable-select').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });
        });

        // Filter toggle for mobile
        function toggleFilters() {
            const filtersContainer = document.getElementById('filters-container');
            filtersContainer.classList.toggle('hidden');
        }
    </script>
@endsection