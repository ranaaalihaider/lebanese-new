@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-stone-50 pb-20 md:pb-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl md:text-3xl font-bold text-white">@trans('Payout Requests')</h1>
                    <p class="text-blue-50 text-sm md:text-base mt-1">@trans('Seller payout requests awaiting review')</p>
                </div>
                <a href="{{ route('admin.payouts.index') }}"
                    class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl flex items-center gap-2 transition-colors text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    @trans('Back to Payouts')
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-8">
        @if($requests->count() > 0)
            <!-- Mobile: Card Layout -->
            <div class="md:hidden space-y-3">
                @foreach($requests as $request)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-stone-900">
                                    {{ $request->seller->sellerProfile->store_name ?? $request->seller->name }}
                                </h3>
                                <p class="text-xs text-stone-500 mt-0.5">@trans('Requested') {{ $request->requested_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @trans('Pending')
                            </span>
                        </div>

                        <!-- Amounts -->
                        <div class="bg-stone-50 rounded-xl p-3 mb-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-stone-600">@trans('Requested Amount')</span>
                                <span class="font-bold text-stone-900">${{ number_format($request->requested_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-stone-600">@trans('Current Pending')</span>
                                <span class="font-bold text-blue-600">${{ number_format($request->current_pending, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('admin.payouts.index', ['seller_id' => $request->seller_id, 'payout_status' => 'pending']) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            @trans('View Pending Payouts')
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Desktop: Table -->
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-200 text-xs uppercase text-stone-500 font-bold tracking-wider">
                                <th class="px-6 py-4">@trans('Store Name')</th>
                                <th class="px-6 py-4">@trans('Requested Amount')</th>
                                <th class="px-6 py-4">@trans('Current Pending')</th>
                                <th class="px-6 py-4">@trans('Request Date')</th>
                                <th class="px-6 py-4 text-right">@trans('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @foreach($requests as $request)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">
                                            <a href="{{ route('admin.stores.show', $request->seller_id) }}"
                                                class="hover:text-blue-600 hover:underline transition-colors">
                                                {{ $request->seller->sellerProfile->store_name ?? $request->seller->name }}
                                            </a>
                                        </div>
                                        @if($request->seller->sellerProfile)
                                            <div class="text-xs text-stone-500 font-mono mt-1">
                                                {{ $request->seller->sellerProfile->bank_name ?? 'No Bank' }} -
                                                {{ $request->seller->sellerProfile->account_number ?? 'No Acc #' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">${{ number_format($request->requested_amount, 2) }}</div>
                                        <div class="text-xs text-stone-500">@trans('At time of request')</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-blue-600">${{ number_format($request->current_pending, 2) }}</div>
                                        <div class="text-xs text-stone-500">@trans('Current balance')</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-stone-900">{{ $request->requested_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-stone-500">{{ $request->requested_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.payouts.index', ['seller_id' => $request->seller_id, 'payout_status' => 'pending']) }}"
                                            class="inline-flex items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 text-sm font-bold px-4 py-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            @trans('View Payouts')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="text-lg font-bold text-stone-900">@trans('No payout requests')</h3>
                <p class="text-stone-500 mt-1">@trans('Sellers haven\'t requested any payouts yet')</p>
            </div>
        @endif
    </div>
</div>

<!-- Mobile Bottom Navigation -->
@include('components.bottom-nav')
@endsection
