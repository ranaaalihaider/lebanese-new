@extends('layouts.app')

@section('content')
    <!-- Static Header -->
    <div class="bg-white border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('seller.earnings') }}" class="text-stone-500 hover:text-stone-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-lg font-bold text-stone-900">@trans('Payout Requests')</h1>
            </div>
            <!-- Action Button (Mobile Context) -->
            @if($pendingPayouts > 0 && !$hasActiveRequest)
                <form action="{{ route('seller.payout.request') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm hover:bg-emerald-700">
                        @trans('Request') ${{ number_format($pendingPayouts, 0) }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Sticky Tabs -->
    <div class="sticky top-14 md:top-16 z-30 bg-white/95 backdrop-blur-sm border-b border-stone-200 shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex overflow-x-auto scrollbar-hide px-4 gap-6 text-sm font-medium">
                <a href="{{ route('seller.payout.requests', ['status' => 'pending']) }}"
                    class="py-3 border-b-2 whitespace-nowrap {{ $status === 'pending' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-stone-500' }}">
                    @trans('Pending')
                </a>
                <a href="{{ route('seller.payout.requests', ['status' => 'approved']) }}"
                    class="py-3 border-b-2 whitespace-nowrap {{ $status === 'approved' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-stone-500' }}">
                    @trans('Approved')
                </a>
                <a href="{{ route('seller.payout.requests', ['status' => 'rejected']) }}"
                    class="py-3 border-b-2 whitespace-nowrap {{ $status === 'rejected' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-stone-500' }}">
                    @trans('Rejected')
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-4">
        <!-- Status Banners -->
        @if($hasActiveRequest && $status === 'pending')
            <div class="mb-4 bg-blue-50 border border-blue-100 rounded-lg p-3 flex items-center gap-3">
                <div class="bg-blue-100 p-1.5 rounded-full shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-blue-900">@trans('Request in Progress')</p>
                    <p class="text-xs text-blue-700">@trans('We are processing your payout.')</p>
                </div>
            </div>
        @endif

        @if($requests->count() > 0)
            <!-- List Layout (Mobile & Desktop Unified for simplicity/consistency or Mobile Cards + Desktop Table) -->
            <div class="space-y-3">
                @foreach($requests as $request)
                    <div
                        class="bg-white rounded-xl border border-stone-100 shadow-sm p-4 hover:border-emerald-100 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span
                                    class="text-2xl font-bold text-stone-900">${{ number_format($request->requested_amount, 2) }}</span>
                                <p class="text-xs text-stone-500">{{ $request->requested_at->format('M d, Y h:i A') }}</p>
                            </div>

                            {{-- Status Badge --}}
                            @if($status === 'pending')
                                <span
                                    class="bg-orange-50 text-orange-600 text-xs px-2 py-1 rounded-md font-bold uppercase tracking-wide">@trans('Pending')</span>
                            @elseif($status === 'approved')
                                <span
                                    class="bg-emerald-50 text-emerald-600 text-xs px-2 py-1 rounded-md font-bold uppercase tracking-wide">@trans('Paid')</span>
                            @else
                                <span
                                    class="bg-red-50 text-red-600 text-xs px-2 py-1 rounded-md font-bold uppercase tracking-wide">@trans('Rejected')</span>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        @if($status === 'approved' && $request->processed_at)
                            <div class="mt-3 pt-3 border-t border-stone-50 flex items-center gap-2 text-xs text-emerald-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @trans('Processed on') {{ $request->processed_at->format('M d') }}
                            </div>
                        @elseif($status === 'rejected' && $request->admin_notes)
                            <div class="mt-3 pt-3 border-t border-stone-50 bg-red-50/50 -mx-4 -mb-4 px-4 py-3 rounded-b-xl">
                                <p class="text-xs font-bold text-red-800 mb-0.5">@trans('Reason:')</p>
                                <p class="text-sm text-red-700 leading-snug">{{ $request->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->appends(['status' => $status])->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-stone-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-stone-900 font-bold">@trans('No requests')</h3>
                <p class="text-stone-500 text-sm">@trans('You have no ' . $status . ' payout requests.')</p>
            </div>
        @endif
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
@endsection