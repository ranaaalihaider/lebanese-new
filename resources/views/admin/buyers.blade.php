@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-stone-50 pb-20 md:pb-6 pt-16 md:pt-6">

        <!-- Header Section -->
        <div class="max-w-7xl mx-auto px-4 md:px-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">@trans('Buyers')</h1>
                    <p class="text-sm text-stone-500 mt-1">@trans('Manage registered customers') • {{ $buyers->total() }}
                        total</p>
                </div>

                <!-- Filter Toggle (Mobile & Desktop) -->
                <div>
                    <button type="button" onclick="toggleBuyerFilters()"
                        class="w-full md:w-auto px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg flex items-center justify-center gap-2 hover:bg-stone-50 transition-colors text-sm font-semibold shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        @trans('Search')
                    </button>
                </div>
            </div>

            <!-- Expandable Filters -->
            <div id="buyer-filters-container" class="mt-4 {{ request('search') ? '' : 'hidden' }}">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-stone-200">
                    <form method="GET" class="flex flex-col md:flex-row gap-3 items-end">
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-semibold text-stone-500 mb-1 block">@trans('Search Buyers')</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Name, Email, Phone..."
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-sm focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="submit"
                                class="flex-1 md:flex-none px-4 py-2 bg-stone-900 text-white rounded-lg text-sm font-bold hover:bg-stone-800 transition-colors">
                                @trans('Search')
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.buyers') }}"
                                    class="px-4 py-2 bg-stone-100 text-stone-600 rounded-lg text-sm font-bold hover:bg-stone-200 transition-colors">
                                    @trans('Reset')
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <script>
                function toggleBuyerFilters() {
                    document.getElementById('buyer-filters-container').classList.toggle('hidden');
                }
            </script>

            <!-- Stats Overview (Desktop) -->
            <div class="hidden md:grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-stone-200 p-4">
                    <p class="text-xs text-stone-500 uppercase font-bold">@trans('Total Buyers')</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1">{{ $totalBuyers }}</p>
                </div>
                <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4">
                    <p class="text-xs text-emerald-600 uppercase font-bold">Active (30d)</p>
                    <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $activeBuyers }}</p>
                </div>
                <div class="bg-purple-50 rounded-xl border border-purple-200 p-4">
                    <p class="text-xs text-purple-600 uppercase font-bold">@trans('In Results')</p>
                    <p class="text-2xl font-bold text-purple-700 mt-1">{{ $buyers->total() }}</p>
                </div>
            </div>

            <!-- Mobile Stats Pills -->
            <div class="md:hidden flex gap-2 pb-4 overflow-x-auto scrollbar-hide">
                <div class="bg-white rounded-lg border border-stone-200 px-3 py-2 flex-shrink-0 shadow-sm min-w-[100px]">
                    <p class="text-[10px] text-stone-500 uppercase font-bold">@trans('Total')</p>
                    <p class="text-lg font-bold text-stone-900 leading-none mt-1">{{ $totalBuyers }}</p>
                </div>
                <div class="bg-emerald-50 rounded-lg border border-emerald-100 px-3 py-2 flex-shrink-0 shadow-sm min-w-[100px]">
                    <p class="text-[10px] text-emerald-600 uppercase font-bold">Active</p>
                    <p class="text-lg font-bold text-emerald-700 leading-none mt-1">{{ $activeBuyers }}</p>
                </div>
            </div>


            @if($buyers->count() > 0)
                <!-- Mobile: Compact List -->
                <div class="md:hidden space-y-3">
                    @foreach($buyers as $buyer)
                        <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                            <a href="{{ route('admin.buyers.show', $buyer->id) }}" class="block p-3">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 flex-shrink-0 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm border border-stone-100 shadow-sm">
                                        {{ strtoupper(substr($buyer->name, 0, 1)) }}
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-stone-900 text-sm truncate pr-2">{{ $buyer->name }}</h3>
                                            <svg class="w-4 h-4 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-stone-500 truncate">{{ $buyer->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-2 pt-2 border-t border-stone-50 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-stone-400">Joined {{ $buyer->created_at->format('M d') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold text-emerald-600">
                                            ${{ number_format($buyer->buyer_orders_sum_total_price ?? 0, 2) }}
                                        </span>
                                        <span class="text-[10px] font-medium text-stone-400 bg-stone-100 px-1.5 py-0.5 rounded">
                                            {{ $buyer->buyer_orders_count ?? 0 }} orders
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop: Professional Table -->
                <div class="hidden md:block bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200">
                            <thead class="bg-stone-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Buyer')</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Contact')</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Orders')</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Total Spent')</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-stone-200">
                                @foreach($buyers as $buyer)
                                    <tr class="hover:bg-stone-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                                    {{ strtoupper(substr($buyer->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-stone-900 leading-none mb-1">{{ $buyer->name }}</p>
                                                    <p class="text-xs text-stone-500 leading-none">Joined
                                                        {{ $buyer->created_at->format('M Y') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-stone-700 leading-tight">{{ $buyer->email }}</p>
                                            <p class="text-xs text-stone-400 leading-tight">{{ $buyer->phone }}</p>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-stone-100 text-stone-700">
                                                {{ $buyer->buyer_orders_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <span
                                                class="text-sm font-bold text-emerald-600">${{ number_format($buyer->buyer_orders_sum_total_price ?? 0, 2) }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <a href="{{ route('admin.buyers.show', $buyer->id) }}"
                                                class="text-stone-400 hover:text-emerald-600 font-bold text-xs uppercase tracking-wide transition-colors">
                                                @trans('View Profile')
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
                    <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-stone-900">@trans('No buyers found')</h3>
                    <p class="text-stone-500 mt-1">@trans('Try adjusting your search')</p>
                </div>
            @endif
        </div>
    </div>
@endsection