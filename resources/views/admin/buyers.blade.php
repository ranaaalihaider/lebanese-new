@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-stone-50 pb-20 md:pb-6">
    <!-- WhatsApp-Inspired Mobile Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 md:bg-white md:border-b md:border-stone-200">
        <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
            <!-- Mobile Header (WhatsApp Style) -->
            <div class="md:hidden">
                <h1 class="text-xl font-bold text-white">Buyers</h1>
                <p class="text-emerald-100 text-sm mt-0.5">{{ $buyers->total() }} customers</p>
            </div>
            
            <!-- Desktop Header -->
            <div class="hidden md:block">
                <h1 class="text-2xl md:text-3xl font-bold text-white">Buyer Management</h1>
                <p class="text-emerald-50 text-sm md:text-base mt-1">Manage customer accounts and track activity</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-0 md:px-6 py-0 md:py-8">
        <!-- Search Bar (WhatsApp Style for Mobile) -->
        <div class="bg-white md:rounded-2xl md:shadow-sm md:border md:border-stone-200 p-3 md:p-6 mb-3 md:mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="hidden md:block text-lg font-bold text-stone-900">Search</h2>
                <button type="button" onclick="toggleBuyerFilters()"
                    class="hidden md:flex px-4 py-2 bg-stone-100 text-stone-600 rounded-xl items-center gap-2 hover:bg-stone-200 transition-colors text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Toggle Search
                </button>
            </div>
            <div id="buyer-filters-container" class="{{ request('search') ? '' : 'hidden' }}">
            <form method="GET" class="flex gap-2">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search buyers..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-stone-100 md:bg-white border-0 md:border md:border-stone-300 rounded-full md:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                </div>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-full md:rounded-xl font-bold transition-colors">
                    <span class="hidden md:inline">Search</span>
                    <svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.buyers') }}" class="bg-stone-200 hover:bg-stone-300 text-stone-700 px-4 py-2.5 rounded-full md:rounded-xl font-bold transition-colors">
                        Clear
                    </a>
                @endif
            </form>
            </div>
        </div>

        <script>
            function toggleBuyerFilters() {
                document.getElementById('buyer-filters-container').classList.toggle('hidden');
            }
        </script>

        <!-- Stats (Mobile Pills) -->
        <div class="md:hidden flex gap-2 px-4 pb-3 overflow-x-auto scrollbar-hide">
            <div class="bg-white rounded-2xl px-4 py-2 flex-shrink-0 shadow-sm">
                <p class="text-xs text-stone-500">Total</p>
                <p class="text-lg font-bold text-stone-900">{{ $totalBuyers }}</p>
            </div>
            <div class="bg-white rounded-2xl px-4 py-2 flex-shrink-0 shadow-sm">
                <p class="text-xs text-stone-500">Active</p>
                <p class="text-lg font-bold text-emerald-600">{{ $activeBuyers }}</p>
            </div>
            <div class="bg-white rounded-2xl px-4 py-2 flex-shrink-0 shadow-sm">
                <p class="text-xs text-stone-500">Results</p>
                <p class="text-lg font-bold text-purple-600">{{ $buyers->total() }}</p>
            </div>
        </div>

        <!-- Desktop Stats -->
        <div class="hidden md:grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-stone-200 p-4">
                <p class="text-xs text-stone-500 uppercase font-bold">Total Buyers</p>
                <p class="text-2xl font-bold text-stone-900 mt-1">{{ $totalBuyers }}</p>
            </div>
            <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4">
                <p class="text-xs text-emerald-600 uppercase font-bold">Active (30d)</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $activeBuyers }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl border border-purple-200 p-4">
                <p class="text-xs text-purple-600 uppercase font-bold">Results</p>
                <p class="text-2xl font-bold text-purple-700 mt-1">{{ $buyers->total() }}</p>
            </div>
        </div>

        @if($buyers->count() > 0)
            <!-- Mobile: WhatsApp-Style List -->
            <div class="md:hidden bg-white">
                @foreach($buyers as $buyer)
                    <a href="{{ route('admin.buyers.show', $buyer->id) }}" 
                        class="flex items-center gap-3 p-4 border-b border-stone-100 active:bg-stone-50 transition-colors">
                        <!-- Avatar Circle (WhatsApp Style) -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                {{ strtoupper(substr($buyer->name, 0, 1)) }}
                            </div>
                        </div>

                        <!-- Content (WhatsApp Style) -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline justify-between mb-0.5">
                                <h3 class="font-semibold text-stone-900 truncate">{{ $buyer->name }}</h3>
                                <span class="text-xs text-stone-400 ml-2">{{ $buyer->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-stone-500 truncate">{{ $buyer->email }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-emerald-600 font-bold">${{ number_format($buyer->buyer_orders_sum_total_price ?? 0, 2) }}</span>
                                <span class="text-xs text-stone-400">• {{ $buyer->buyer_orders_count ?? 0 }} orders</span>
                            </div>
                        </div>

                        <!-- Chevron -->
                        <svg class="w-5 h-5 text-stone-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endforeach
            </div>

            <!-- Desktop: Professional Table -->
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Buyer</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-stone-500 uppercase tracking-wider">Orders</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">Total Spent</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($buyers as $buyer)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold shadow-sm">
                                                {{ strtoupper(substr($buyer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-stone-900">{{ $buyer->name }}</p>
                                                <p class="text-xs text-stone-500">Joined {{ $buyer->created_at->format('M Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-stone-700">{{ $buyer->email }}</p>
                                        <p class="text-xs text-stone-500">{{ $buyer->phone }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-stone-100 border border-stone-200">
                                            <span class="text-sm font-bold text-stone-700">{{ $buyer->buyer_orders_count ?? 0 }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-emerald-600">${{ number_format($buyer->buyer_orders_sum_total_price ?? 0, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('admin.buyers.show', $buyer->id) }}" 
                                            class="bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm">
                                            View Profile
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 px-4 md:px-0">
                {{ $buyers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center mx-4 md:mx-0">
                <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-stone-900">No buyers found</h3>
                <p class="text-stone-500 mt-1">Try adjusting your search</p>
            </div>
        @endif
    </div>
</div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection