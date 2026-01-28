@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-stone-50 pb-20 md:pb-6">
        <!-- WhatsApp-Inspired Mobile Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 md:bg-white md:border-b md:border-stone-200">
            <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
                <!-- Mobile Header (WhatsApp Style) -->
                <div class="md:hidden">
                    <h1 class="text-xl font-bold text-white">@trans('Sellers')</h1>
                    <p class="text-emerald-100 text-sm mt-0.5">{{ $sellers->total() }} sellers</p>
                </div>

                <!-- Desktop Header -->
                <div class="hidden md:block">
                    <h1 class="text-2xl md:text-3xl font-bold text-white">@trans('Seller Management')</h1>
                    <p class="text-emerald-50 text-sm md:text-base mt-1">@trans('Manage seller accounts and approvals')</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-0 md:px-6 py-0 md:py-8">
            <!-- Search Bar (WhatsApp Style for Mobile) -->
            <div class="bg-white md:rounded-2xl md:shadow-sm md:border md:border-stone-200 p-3 md:p-6 mb-3 md:mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="hidden md:block text-lg font-bold text-stone-900">@trans('Filters')</h2>
                    <button type="button" onclick="toggleSellerFilters()"
                        class="hidden md:flex px-4 py-2 bg-stone-100 text-stone-600 rounded-xl items-center gap-2 hover:bg-stone-200 transition-colors text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        @trans('Toggle Filters')
                    </button>
                </div>
                <div id="seller-filters-container" class="{{ request()->anyFilled(['search', 'status']) ? '' : 'hidden' }}">
                    <form method="GET" class="flex flex-col md:flex-row gap-3 md:gap-4 items-stretch md:items-end">
                        <!-- Search -->
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-stone-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sellers..."
                                class="w-full pl-10 pr-4 py-2.5 bg-stone-100 md:bg-white border-0 md:border md:border-stone-300 rounded-full md:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        </div>

                        <!-- Status Filter -->
                        <div class="md:w-48">
                            <select name="status"
                                class="w-full px-3 py-2.5 border border-stone-300 rounded-full md:rounded-xl focus:ring-2 focus:ring-emerald-500 bg-white">
                                <option value="">@trans('All Status')</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>@trans('Pending')
                                </option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>@trans('Active')</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>@trans('Rejected')
                                </option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-full md:rounded-xl font-bold transition-colors">
                            @trans('Apply')
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.sellers') }}"
                                class="bg-stone-200 hover:bg-stone-300 text-stone-700 px-4 py-2.5 rounded-full md:rounded-xl font-bold transition-colors text-center">
                                @trans('Clear')
                            </a>
                        @endif

                        <!-- Mobile Status Filter Pills -->
                        <div class="md:hidden flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                            <a href="{{ route('admin.sellers') }}"
                                class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap {{ !request('status') ? 'bg-emerald-600 text-white' : 'bg-stone-100 text-stone-600' }}">
                                All ({{ $pendingCount + $activeCount + $rejectedCount }})
                            </a>
                            <a href="{{ route('admin.sellers', ['status' => 'pending']) }}"
                                class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap {{ request('status') == 'pending' ? 'bg-amber-500 text-white' : 'bg-stone-100 text-stone-600' }}">
                                Pending ({{ $pendingCount }})
                            </a>
                            <a href="{{ route('admin.sellers', ['status' => 'active']) }}"
                                class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap {{ request('status') == 'active' ? 'bg-emerald-500 text-white' : 'bg-stone-100 text-stone-600' }}">
                                Active ({{ $activeCount }})
                            </a>
                            <a href="{{ route('admin.sellers', ['status' => 'rejected']) }}"
                                class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap {{ request('status') == 'rejected' ? 'bg-red-500 text-white' : 'bg-stone-100 text-stone-600' }}">
                                Rejected ({{ $rejectedCount }})
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function toggleSellerFilters() {
                    document.getElementById('seller-filters-container').classList.toggle('hidden');
                }
            </script>

            @if($sellers->count() > 0)
                <!-- Mobile: WhatsApp-Style List -->
                <div class="md:hidden bg-white">
                    @foreach($sellers as $seller)
                        <div class="border-b border-stone-100 active:bg-stone-50 transition-colors">
                            <a href="{{ route('admin.stores.show', $seller->id) }}" class="flex items-center gap-3 p-4">
                                <!-- Avatar Circle (WhatsApp Style) -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                        {{ strtoupper(substr($seller->sellerProfile->store_name ?? $seller->name, 0, 1)) }}
                                    </div>
                                </div>

                                <!-- Content (WhatsApp Style) -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline justify-between mb-0.5">
                                        <h3 class="font-semibold text-stone-900 truncate">
                                            {{ $seller->sellerProfile->store_name ?? $seller->name }}
                                        </h3>
                                        <span class="text-xs text-stone-400 ml-2">{{ $seller->created_at->format('M d') }}</span>
                                    </div>
                                    <p class="text-sm text-stone-500 truncate">{{ $seller->email }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                                                                                                        @if($seller->status === 'pending') bg-amber-100 text-amber-700
                                                                                                        @elseif($seller->status === 'active') bg-emerald-100 text-emerald-700
                                                                                                        @else bg-red-100 text-red-700
                                                                                                        @endif">
                                            {{ ucfirst($seller->status) }}
                                        </span>
                                        <span class="text-xs text-stone-400">• {{ $seller->products_count ?? 0 }} products</span>
                                    </div>
                                </div>

                                <!-- Chevron -->
                                <svg class="w-5 h-5 text-stone-300 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop: Professional Table -->
                <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200">
                            <thead class="bg-stone-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Seller')</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Contact')</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Products')</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Status')</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                        @trans('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-stone-200">
                                @foreach($sellers as $seller)
                                    <tr class="hover:bg-stone-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold shadow-sm">
                                                    {{ strtoupper(substr($seller->sellerProfile->store_name ?? $seller->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-stone-900">
                                                        {{ $seller->sellerProfile->store_name ?? 'No Store Name' }}
                                                    </p>
                                                    <p class="text-xs text-stone-500">{{ $seller->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-stone-700">{{ $seller->email }}</p>
                                            <p class="text-xs text-stone-500">{{ $seller->phone }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 border border-blue-200">
                                                <span
                                                    class="text-sm font-bold text-blue-700">{{ $seller->products_count ?? 0 }}</span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold
                                                                                                            @if($seller->status === 'pending') bg-amber-100 text-amber-700 border border-amber-200
                                                                                                            @elseif($seller->status === 'active') bg-emerald-100 text-emerald-700 border border-emerald-200
                                                                                                            @else bg-red-100 text-red-700 border border-red-200
                                                                                                            @endif">
                                                {{ ucfirst($seller->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.stores.show', $seller->id) }}"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                                                    @trans('View Store')
                                                </a>

                                                @if($seller->status === 'pending')
                                                    {{-- Pending: Show Approve and Reject --}}
                                                    <form method="POST" action="{{ route('admin.sellers.approve', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                                                            @trans('Approve')
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.sellers.reject', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                                                            @trans('Reject')
                                                        </button>
                                                    </form>
                                                @elseif($seller->status === 'active')
                                                    {{-- Active: Show Deactivate --}}
                                                    <form method="POST" action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                                                            @trans('Deactivate')
                                                        </button>
                                                    </form>
                                                @elseif($seller->status === 'rejected')
                                                    {{-- Rejected: Show Activate --}}
                                                    <form method="POST" action="{{ route('admin.sellers.activate', $seller->id) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                                                            @trans('Activate')
                                                        </button>
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