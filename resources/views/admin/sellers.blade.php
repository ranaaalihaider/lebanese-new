@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-stone-50 pb-20 md:pb-6 pt-16 md:pt-6">

        <!-- Header Section -->
        <div class="max-w-7xl mx-auto px-4 md:px-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">@trans('Sellers')</h1>
                    <p class="text-sm text-stone-500 mt-1">@trans('Manage registered sellers') • {{ $sellers->total() }}
                        total</p>
                </div>

                <!-- Filter Toggle (Mobile & Desktop) -->
                <div>
                    <button type="button" onclick="toggleSellerFilters()"
                        class="w-full md:w-auto px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg flex items-center justify-center gap-2 hover:bg-stone-50 transition-colors text-sm font-semibold shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        @trans('Filter & Search')
                    </button>
                </div>
            </div>

            <!-- Expandable Filters -->
            <div id="seller-filters-container"
                class="mt-4 {{ request()->anyFilled(['search', 'status']) ? '' : 'hidden' }}">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-stone-200">
                    <form method="GET" class="flex flex-col md:flex-row gap-3 items-end">
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-semibold text-stone-500 mb-1 block">@trans('Search')</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Name, Email, Store..."
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-sm focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        </div>
                        <div class="w-full md:w-48">
                            <label class="text-xs font-semibold text-stone-500 mb-1 block">@trans('Status')</label>
                            <select name="status"
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-sm focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                                <option value="">@trans('All')</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    @trans('Pending')</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>@trans('Active')
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    @trans('Rejected')</option>
                            </select>
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="submit"
                                class="flex-1 md:flex-none px-4 py-2 bg-stone-900 text-white rounded-lg text-sm font-bold hover:bg-stone-800 transition-colors">
                                @trans('Apply')
                            </button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.sellers') }}"
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
                function toggleSellerFilters() {
                    document.getElementById('seller-filters-container').classList.toggle('hidden');
                }
            </script>

            @if($sellers->count() > 0)
                <!-- Mobile: Compact Cards -->
                <div class="md:hidden space-y-3">
                    @foreach($sellers as $seller)
                        <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                            <div class="p-3">
                                <a href="{{ route('admin.stores.show', $seller->id) }}" class="flex items-center gap-3">
                                    <!-- Avatar -->
                                    <div
                                        class="w-10 h-10 flex-shrink-0 rounded-full flex items-center justify-center font-bold text-sm border border-stone-200 overflow-hidden
                                                                {{ ($seller->sellerProfile && $seller->sellerProfile->store_photo) ? 'bg-stone-100' : 'bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-sm' }}">
                                        @if($seller->sellerProfile && $seller->sellerProfile->store_photo)
                                            <img src="{{ asset('storage/' . $seller->sellerProfile->store_photo) }}" alt=""
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($seller->sellerProfile->store_name ?? $seller->name, 0, 1)) }}
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-stone-900 text-sm truncate pr-2">
                                                {{ $seller->sellerProfile->store_name ?? $seller->name }}
                                            </h3>
                                        </div>
                                        <p class="text-xs text-stone-500 truncate">{{ $seller->email }}</p>
                                    </div>
                                </a>

                                <div class="mt-3 pt-3 border-t border-stone-50 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                                                                @if($seller->status === 'pending') bg-amber-50 text-amber-600 border border-amber-100
                                                                                @elseif($seller->status === 'active') bg-emerald-50 text-emerald-600 border border-emerald-100
                                                                                @else bg-red-50 text-red-600 border border-red-100
                                                                                @endif">
                                            {{ $seller->status }}
                                        </span>
                                        <span class="text-[10px] font-medium text-stone-500">
                                            <b class="text-stone-900">{{ $seller->products_count ?? 0 }}</b> products
                                        </span>
                                    </div>

                                    <!-- Mobile Actions -->
                                    <div class="flex items-center gap-2">
                                        @if($seller->status === 'pending')
                                            <form method="POST" action="{{ route('admin.sellers.approve', $seller->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-emerald-600 hover:text-emerald-700 font-bold text-xs uppercase tracking-wide">@trans('Approve')</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.sellers.reject', $seller->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-600 font-bold text-xs uppercase tracking-wide">@trans('Reject')</button>
                                            </form>
                                        @elseif($seller->status === 'active')
                                            <form method="POST" action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-orange-500 hover:text-orange-600 font-bold text-xs uppercase tracking-wide">@trans('Deactivate')</button>
                                            </form>
                                        @elseif($seller->status === 'rejected')
                                            <form method="POST" action="{{ route('admin.sellers.activate', $seller->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-emerald-600 hover:text-emerald-700 font-bold text-xs uppercase tracking-wide">@trans('Enable')</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop: Compact Table -->
                <div class="hidden md:block bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200">
                            <thead class="bg-stone-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Seller')</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Contact')</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Products')</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Status')</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-stone-200">
                                @foreach($sellers as $seller)
                                    <tr class="hover:bg-stone-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border border-stone-200 overflow-hidden
                                                                {{ ($seller->sellerProfile && $seller->sellerProfile->store_photo) ? 'bg-stone-100' : 'bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-sm' }}">
                                                    @if($seller->sellerProfile && $seller->sellerProfile->store_photo)
                                                        <img src="{{ asset('storage/' . $seller->sellerProfile->store_photo) }}" alt=""
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($seller->sellerProfile->store_name ?? $seller->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-stone-900 leading-none mb-1">
                                                        {{ $seller->sellerProfile->store_name ?? 'No Store Name' }}
                                                    </p>
                                                    <p class="text-xs text-stone-500 leading-none">{{ $seller->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-stone-700 leading-tight">{{ $seller->email }}</p>
                                            <p class="text-xs text-stone-400 leading-tight">{{ $seller->phone }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <span class="text-sm font-bold text-stone-700">{{ $seller->products_count ?? 0 }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wide
                                                                                    @if($seller->status === 'pending') bg-amber-50 text-amber-600 border border-amber-100
                                                                                    @elseif($seller->status === 'active') bg-emerald-50 text-emerald-600 border border-emerald-100
                                                                                    @else bg-red-50 text-red-600 border border-red-100
                                                                                    @endif">
                                                {{ $seller->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.stores.show', $seller->id) }}"
                                                    class="text-stone-400 hover:text-emerald-600 font-bold text-xs uppercase tracking-wide transition-colors">
                                                    @trans('Details')
                                                </a>

                                                @if($seller->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.sellers.approve', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-emerald-600 hover:text-emerald-700 font-bold text-xs uppercase tracking-wide transition-colors ml-2">@trans('Approve')</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.sellers.reject', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-red-500 hover:text-red-600 font-bold text-xs uppercase tracking-wide transition-colors ml-2">@trans('Reject')</button>
                                                    </form>
                                                @elseif($seller->status === 'active')
                                                    <form method="POST" action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-orange-500 hover:text-orange-600 font-bold text-xs uppercase tracking-wide transition-colors ml-2">@trans('Deactivate')</button>
                                                    </form>
                                                @elseif($seller->status === 'rejected')
                                                    <form method="POST" action="{{ route('admin.sellers.activate', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-emerald-600 hover:text-emerald-700 font-bold text-xs uppercase tracking-wide transition-colors ml-2">@trans('Re-Activate')</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 px-4 md:px-0">
                    {{ $sellers->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center mx-4 md:mx-0">
                    <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h3 class="text-lg font-bold text-stone-900">@trans('No sellers found')</h3>
                    <p class="text-stone-500 mt-1">@trans('Try adjusting your search or filters')</p>
                </div>
            @endif
        </div>
    </div>
@endsection