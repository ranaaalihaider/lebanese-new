@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-stone-50 pb-20 md:pb-6">
    <!-- WhatsApp-Inspired Mobile Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700">
        <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
            <!-- Mobile Header -->
            <div class="md:hidden">
                <h1 class="text-xl font-bold text-white">@trans('Payouts')</h1>
                <p class="text-emerald-100 text-sm mt-0.5">{{ $orders->total() }} transactions</p>
            </div>
            
            <!-- Desktop Header -->
            <div class="hidden md:block">
                <h1 class="text-2xl md:text-3xl font-bold text-white">@trans('Seller Payouts')</h1>
                <p class="text-emerald-50 text-sm md:text-base mt-1">@trans('Manage and release seller earnings')</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-0 md:px-6 py-0 md:py-8">
        <!-- Mobile Stats Pills -->
        <div class="md:hidden flex gap-2 px-4 py-3 overflow-x-auto scrollbar-hide bg-white border-b border-stone-100">
            <div class="bg-white rounded-2xl px-4 py-2 flex-shrink-0 shadow-sm border border-orange-100">
                <p class="text-xs text-orange-600 font-bold">@trans('Pending')</p>
                <p class="text-lg font-bold text-orange-700">${{ number_format($pendingPayouts, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl px-4 py-2 flex-shrink-0 shadow-sm border border-emerald-100">
                <p class="text-xs text-emerald-600 font-bold">@trans('Paid Out')</p>
                <p class="text-lg font-bold text-emerald-700">${{ number_format($totalPaidOut, 2) }}</p>
            </div>
        </div>

        <!-- Desktop Payout Stats -->
        <div class="hidden md:grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-200 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-stone-500 uppercase tracking-wide">@trans('Pending Payouts')</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">${{ number_format($pendingPayouts, 2) }}</p>
                </div>
                <div class="p-3 bg-orange-50 rounded-full text-orange-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-200 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-stone-500 uppercase tracking-wide">@trans('Total Paid Out')</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">${{ number_format($totalPaidOut, 2) }}</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white md:rounded-2xl md:shadow-sm md:border md:border-stone-200 p-3 md:p-6 mb-3 md:mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <h2 class="hidden md:block text-xl font-bold text-stone-900">@trans('Transactions')</h2>
                <button onclick="togglePayoutFilters()"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full md:rounded-xl flex items-center gap-2 transition-colors text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    @trans('Toggle Filters')
                </button>
            </div>

            <div id="payout-filters-container"
                class="{{ request()->anyFilled(['search', 'start_date', 'end_date', 'seller_id', 'payout_status']) ? '' : 'hidden' }}">
                <form action="{{ route('admin.payouts.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-3 md:gap-4">

                    <div class="relative flex-1 md:flex-none md:w-48">
                        <input type="text" name="search" placeholder="Search Order#..." value="{{ request('search') }}"
                            class="pl-4 pr-10 py-2.5 md:py-2 rounded-full border-stone-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 w-full">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="flex-1 md:w-36 rounded-full border-stone-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2.5 md:py-2 text-stone-600">
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="flex-1 md:w-36 rounded-full border-stone-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2.5 md:py-2 text-stone-600">
                    </div>

                    <select name="seller_id"
                        class="pl-4 pr-10 py-2.5 md:py-2 rounded-full border-stone-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 md:w-40 bg-white">
                        <option value="all">@trans('All Sellers')</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                {{ Str::limit($seller->sellerProfile->store_name ?? $seller->name, 15) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="payout_status"
                        class="pl-4 pr-10 py-2.5 md:py-2 rounded-full border-stone-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 md:w-36 bg-white">
                        <option value="all" {{ request('payout_status') == 'all' ? 'selected' : '' }}>@trans('All Status')</option>
                        <option value="pending" {{ request('payout_status') == 'pending' ? 'selected' : '' }}>@trans('Pending')</option>
                        <option value="paid" {{ request('payout_status') == 'paid' ? 'selected' : '' }}>@trans('Paid')</option>
                    </select>

                    <button type="submit"
                        class="px-4 py-2.5 md:py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-sm font-bold transition-colors">
                        @trans('Apply Filters')
                    </button>

                    @if(request()->anyFilled(['search', 'start_date', 'end_date', 'seller_id', 'payout_status']) && (request('seller_id') != 'all' || request('payout_status') != 'all'))
                        <a href="{{ route('admin.payouts.index') }}"
                            class="flex items-center justify-center px-4 py-2.5 md:py-2 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-full text-sm font-bold transition-colors">
                            @trans('Clear')
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if($orders->count() > 0)
            <!-- Mobile: Card Layout -->
            <div class="md:hidden bg-white">
                @foreach($orders as $order)
                    <div class="p-4 border-b border-stone-100">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="text-sm font-bold text-emerald-700 hover:text-emerald-800">
                                    #{{ $order->order_number }}
                                </a>
                                <p class="text-xs text-stone-500 mt-0.5">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            @if($order->payout_status === 'paid')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    @trans('Paid')
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @trans('Pending')
                                </span>
                            @endif
                        </div>

                        <!-- Seller Info -->
                        <div class="mb-3">
                            <p class="text-xs text-stone-500 uppercase font-bold mb-1">@trans('Seller')</p>
                            <a href="{{ route('admin.stores.show', $order->seller_id) }}"
                                class="text-sm font-medium text-stone-900 hover:text-emerald-600">
                                {{ $order->seller->sellerProfile->store_name ?? $order->seller->name }}
                            </a>
                            @if($order->seller->sellerProfile)
                                <p class="text-xs text-stone-500 font-mono mt-1">
                                    {{ $order->seller->sellerProfile->bank_name ?? 'No Bank' }} - {{ $order->seller->sellerProfile->account_number ?? 'N/A' }}
                                </p>
                            @endif
                        </div>

                        <!-- Amount -->
                        <div class="bg-stone-50 rounded-xl p-3 mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-stone-600">@trans('Seller Earning')</span>
                                <span class="font-bold text-stone-900">${{ number_format($order->seller_earning, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-500">@trans('Platform Fee')</span>
                                <span class="text-stone-600">${{ number_format($order->platform_fee, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        @if($order->payout_status === 'pending')
                            <button
                                onclick="openPayoutModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->seller_earning }}', '{{ $order->seller->sellerProfile->bank_name ?? '' }}', '{{ $order->seller->sellerProfile->account_title ?? '' }}', '{{ $order->seller->sellerProfile->account_number ?? '' }}')"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold transition-colors">
                                @trans('Release Payout')
                            </button>
                        @else
                            <button onclick='openViewPayoutModal(@json($order))'
                                class="w-full bg-stone-100 hover:bg-stone-200 text-stone-700 px-4 py-2.5 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                @trans('View Details')
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Desktop: Table -->
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-200 text-xs uppercase text-stone-500 font-bold tracking-wider">
                                <th class="px-6 py-4">@trans('Order Details')</th>
                                <th class="px-6 py-4">@trans('Seller Info')</th>
                                <th class="px-6 py-4">@trans('Amount')</th>
                                <th class="px-6 py-4">@trans('Payout Status')</th>
                                <th class="px-6 py-4 text-right">@trans('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @foreach($orders as $order)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="hover:text-emerald-600 hover:underline transition-colors">
                                                Order #{{ $order->order_number }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-stone-500">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-stone-500">Status: {{ ucfirst($order->status) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-stone-900">
                                            <a href="{{ route('admin.stores.show', $order->seller_id) }}"
                                                class="hover:text-emerald-600 hover:underline transition-colors">
                                                {{ $order->seller->sellerProfile->store_name ?? $order->seller->name }}
                                            </a>
                                        </div>
                                        @if($order->seller->sellerProfile)
                                            <div class="text-xs text-stone-500 font-mono mt-1">
                                                {{ $order->seller->sellerProfile->bank_name ?? 'No Bank' }} -
                                                {{ $order->seller->sellerProfile->account_number ?? 'No Acc #' }}
                                            </div>
                                        @else
                                            <span class="text-xs text-red-500">@trans('No Profile')</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">${{ number_format($order->seller_earning, 2) }}</div>
                                        <div class="text-xs text-stone-500">Platform Fee:
                                            ${{ number_format($order->platform_fee, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->payout_status === 'paid')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                @trans('Paid')
                                            </span>
                                            <div class="text-xs text-stone-500 mt-1">{{ $order->payout_date->format('M d, Y') }}</div>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @trans('Pending')
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($order->payout_status === 'pending')
                                            <button
                                                onclick="openPayoutModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->seller_earning }}', '{{ $order->seller->sellerProfile->bank_name ?? '' }}', '{{ $order->seller->sellerProfile->account_title ?? '' }}', '{{ $order->seller->sellerProfile->account_number ?? '' }}')"
                                                class="text-white bg-emerald-600 hover:bg-emerald-700 text-sm font-bold px-4 py-2 rounded-lg transition-colors">
                                                @trans('Release Payout')
                                            </button>
                                        @else
                                            <button onclick='openViewPayoutModal(@json($order))'
                                                class="text-stone-500 hover:text-emerald-600 transition-colors" title="View Payout Details">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 px-4 md:px-0">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center mx-4 md:mx-0">
                <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <h3 class="text-lg font-bold text-stone-900">@trans('No payouts found')</h3>
                <p class="text-stone-500 mt-1">@trans('Try adjusting your filters or check back later')</p>
            </div>
        @endif
    </div>
</div>

<!-- Payout Release Modal -->
<div id="payoutModal"
    class="hidden fixed inset-0 bg-stone-900 bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform transition-all scale-100">
        <div class="p-6 border-b border-stone-100 flex justify-between items-center bg-stone-50 rounded-t-2xl">
            <h3 class="text-lg font-bold text-stone-900">@trans('Release Payout')</h3>
            <button onclick="closePayoutModal()" class="text-stone-400 hover:text-stone-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <form id="payoutForm" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">@trans('Order Number')</label>
                    <p id="modalOrderNumber" class="text-lg font-bold text-stone-900">#</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">@trans('Amount to Pay')</label>
                    <p id="modalAmount" class="text-2xl font-bold text-emerald-600">$0.00</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-stone-700 mb-2">@trans('Payment Method')</label>
                    <select name="payout_method" id="payoutMethodSelect" onchange="toggleBankDetails()"
                        class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Bank Transfer">@trans('Bank Transfer')</option>
                        <option value="Cash">Cash (In-Hand)</option>
                        <option value="Check">@trans('Check')</option>
                        <option value="Other">@trans('Other')</option>
                    </select>
                </div>

                <div id="bankDetailsSection" class="bg-emerald-50 p-3 rounded-lg border border-emerald-100">
                    <p class="text-xs font-bold text-emerald-700 uppercase mb-1">@trans('Seller Bank Details')</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-xs text-emerald-600 block">@trans('Bank Name')</span>
                            <p id="modalBankNameDisplay" class="text-sm text-emerald-900 font-medium">@trans('N/A')</p>
                        </div>
                        <div>
                            <span class="text-xs text-emerald-600 block">@trans('Account Title')</span>
                            <p id="modalAccountTitleDisplay" class="text-sm text-emerald-900 font-medium">@trans('N/A')</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-xs text-emerald-600 block">@trans('Account Number')</span>
                            <p id="modalAccountNumberDisplay" class="text-sm text-emerald-900 font-mono">@trans('N/A')</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-stone-700 mb-2">@trans('Transaction ID / Reference') <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="payout_transaction_id" required
                        class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="e.g. TRX-123456789">
                </div>
            </div>

            <div class="p-6 bg-stone-50 rounded-b-2xl border-t border-stone-100 flex justify-end gap-3">
                <button type="button" onclick="closePayoutModal()"
                    class="px-5 py-2.5 rounded-xl text-stone-600 font-bold hover:bg-stone-200 transition-colors">@trans('Cancel')</button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">Confirm
                    Release</button>
            </div>
        </form>
    </div>
</div>

<!-- View Payout Details Modal -->
<div id="viewPayoutModal"
    class="hidden fixed inset-0 bg-stone-900 bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform transition-all scale-100">
        <div class="p-6 border-b border-stone-100 flex justify-between items-center bg-stone-50 rounded-t-2xl">
            <h3 class="text-lg font-bold text-stone-900">@trans('Payout Details')</h3>
            <button onclick="closeViewPayoutModal()" class="text-stone-400 hover:text-stone-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Status & Amount -->
            <div class="flex justify-between items-center pb-4 border-b border-stone-100">
                <div>
                    <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Order #')</p>
                    <p id="viewOrderNumber" class="text-xl font-bold text-stone-900">#</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Paid Amount')</p>
                    <p id="viewAmount" class="text-2xl font-bold text-emerald-600">$0.00</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Payment Method')</p>
                    <p id="viewMethod" class="text-stone-900 font-medium"></p>
                </div>

                <div>
                    <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Transaction ID')</p>
                    <div class="bg-stone-50 p-2 rounded border border-stone-200">
                        <code id="viewTransactionId" class="text-sm font-mono text-stone-700"></code>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Paid On')</p>
                    <p id="viewDate" class="text-stone-900"></p>
                </div>
            </div>

            <!-- Snapshot Bank Details -->
            <div id="viewBankDetails" class="hidden bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                <h4 class="text-sm font-bold text-emerald-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    @trans('Sent To Bank Account')
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-emerald-700">@trans('Bank:')</span>
                        <span id="viewBankName" class="font-medium text-emerald-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-emerald-700">@trans('Title:')</span>
                        <span id="viewAccountTitle" class="font-medium text-emerald-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-emerald-700">@trans('Account #:')</span>
                        <span id="viewAccountNumber" class="font-mono font-medium text-emerald-900"></span>
                    </div>
                    <div class="mt-2 text-[10px] text-emerald-600 italic text-center">
                        @trans('*Details at time of payout')
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-stone-50 rounded-b-2xl border-t border-stone-100 flex justify-end">
            <button onclick="closeViewPayoutModal()"
                class="px-5 py-2.5 rounded-xl bg-stone-900 text-white font-bold hover:bg-stone-800 transition-colors shadow-lg shadow-stone-200">@trans('Close')</button>
        </div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
@include('components.bottom-nav')

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function openPayoutModal(orderId, orderNumber, amount, bankName, accountTitle, accountNumber) {
        const form = document.getElementById('payoutForm');
        form.action = `/admin/payouts/${orderId}/release`;

        document.getElementById('modalOrderNumber').textContent = '#' + orderNumber;
        document.getElementById('modalAmount').textContent = '$' + parseFloat(amount).toFixed(2);

        document.getElementById('modalBankNameDisplay').textContent = bankName || 'Not Provided';
        document.getElementById('modalAccountTitleDisplay').textContent = accountTitle || 'Not Provided';
        document.getElementById('modalAccountNumberDisplay').textContent = accountNumber || 'Not Provided';

        const select = document.getElementById('payoutMethodSelect');
        select.value = 'Bank Transfer';
        toggleBankDetails();

        document.getElementById('payoutModal').classList.remove('hidden');
    }

    function closePayoutModal() {
        document.getElementById('payoutModal').classList.add('hidden');
    }

    function toggleBankDetails() {
        const method = document.getElementById('payoutMethodSelect').value;
        const section = document.getElementById('bankDetailsSection');
        if (method === 'Bank Transfer') {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }

    function openViewPayoutModal(order) {
        document.getElementById('viewOrderNumber').textContent = '#' + order.order_number;
        document.getElementById('viewAmount').textContent = '$' + parseFloat(order.payout_amount).toFixed(2);
        document.getElementById('viewMethod').textContent = order.payout_method;
        document.getElementById('viewTransactionId').textContent = order.payout_transaction_id;

        const date = new Date(order.payout_date);
        document.getElementById('viewDate').textContent = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });

        const bankSection = document.getElementById('viewBankDetails');

        if (order.payout_method === 'Bank Transfer' && order.payout_details) {
            bankSection.classList.remove('hidden');
            document.getElementById('viewBankName').textContent = order.payout_details.bank_name || 'N/A';
            document.getElementById('viewAccountTitle').textContent = order.payout_details.account_title || 'N/A';
            document.getElementById('viewAccountNumber').textContent = order.payout_details.account_number || 'N/A';
        } else {
            bankSection.classList.add('hidden');
        }

        document.getElementById('viewPayoutModal').classList.remove('hidden');
    }

    function closeViewPayoutModal() {
        document.getElementById('viewPayoutModal').classList.add('hidden');
    }
    
    function togglePayoutFilters() {
        document.getElementById('payout-filters-container').classList.toggle('hidden');
    }
</script>
@endsection
