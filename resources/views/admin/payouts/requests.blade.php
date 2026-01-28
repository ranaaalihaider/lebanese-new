@extends('layouts.app')

@section('content')
    <!-- Static Header -->
    <div class="bg-white border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
             <div class="flex items-center gap-3">
                <a href="{{ route('admin.payouts.index') }}" class="text-stone-500 hover:text-stone-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-stone-900">@trans('Payout Requests')</h1>
             </div>
        </div>
    </div>
    
    <!-- Sticky Tabs -->
    <div class="sticky top-14 md:top-16 z-30 bg-white/95 backdrop-blur-sm border-b border-stone-200 shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex overflow-x-auto scrollbar-hide px-4 gap-6 text-sm font-medium">
                 <a href="{{ route('admin.payout.requests', ['status' => 'pending']) }}"
                    class="py-3 border-b-2 whitespace-nowrap {{ $status === 'pending' ? 'border-orange-500 text-orange-600' : 'border-transparent text-stone-500' }}">
                    @trans('Pending')
                </a>
                <a href="{{ route('admin.payout.requests', ['status' => 'approved']) }}"
                    class="py-3 border-b-2 whitespace-nowrap {{ $status === 'approved' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-stone-500' }}">
                    @trans('Approved')
                </a>
                <a href="{{ route('admin.payout.requests', ['status' => 'rejected']) }}"
                    class="py-3 border-b-2 whitespace-nowrap {{ $status === 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-stone-500' }}">
                    @trans('Rejected')
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-4">
        @if($requests->count() > 0)
            <!-- List Layout (Mobile & Desktop Unified for simplicity/consistency or Mobile Cards + Desktop Table) -->
            <div class="space-y-3 md:hidden">
                @foreach($requests as $request)
                    <div class="bg-white rounded-xl border border-stone-100 shadow-sm p-4 hover:border-blue-100 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                             <div>
                                <h3 class="font-bold text-stone-900 text-sm">{{ $request->seller->sellerProfile->store_name ?? $request->seller->name }}</h3>
                                <p class="text-[10px] text-stone-500">{{ $request->seller->email }}</p>
                             </div>
                             <span class="text-xl font-bold text-stone-900">${{ number_format($request->requested_amount, 2) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2 mb-3">
                             <div class="text-xs text-stone-500">
                                @trans('Requested') {{ $request->requested_at->format('M d, h:i A') }}
                             </div>
                             @if($status === 'pending')
                                <div class="text-xs text-blue-600 font-medium">
                                    @trans('Pending Balance'): ${{ number_format($request->current_pending, 2) }}
                                </div>
                             @endif
                        </div>

                        {{-- Metadata --}}
                        @if($status === 'approved' && $request->processed_at)
                             <div class="mt-3 pt-3 border-t border-stone-50 flex items-center gap-2 text-xs text-emerald-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @trans('Processed by') {{ $request->processedBy->name }}
                             </div>
                        @elseif($status === 'rejected' && $request->admin_notes)
                            <div class="mt-3 pt-3 border-t border-stone-50 bg-red-50/50 -mx-4 -mb-4 px-4 py-3 rounded-b-xl">
                                <p class="text-xs font-bold text-red-800 mb-0.5">@trans('Reason:')</p>
                                <p class="text-sm text-red-700 leading-snug">{{ $request->admin_notes }}</p>
                            </div>
                        @endif

                        <!-- Actions -->
                        @if($status === 'pending')
                            <div class="grid grid-cols-2 gap-2 mt-3">
                                <a href="{{ route('admin.payouts.index', ['seller_id' => $request->seller_id, 'payout_status' => 'pending']) }}"
                                    class="flex items-center justify-center bg-blue-50 text-blue-700 hover:bg-blue-100 py-2 rounded-lg text-xs font-bold transition-colors">
                                    @trans('Review')
                                </a>
                                <button onclick="openRejectModal({{ $request->id }}, '{{ $request->seller->sellerProfile->store_name ?? $request->seller->name }}')"
                                    class="flex items-center justify-center bg-red-50 text-red-700 hover:bg-red-100 py-2 rounded-lg text-xs font-bold transition-colors">
                                    @trans('Reject')
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Desktop: Table -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-200 text-xs uppercase text-stone-500 font-bold tracking-wider">
                                <th class="px-6 py-4">@trans('Store Name')</th>
                                <th class="px-6 py-4">@trans('Amount')</th>
                                @if($status === 'pending')
                                    <th class="px-6 py-4">@trans('Current Pending')</th>
                                @endif
                                <th class="px-6 py-4">@trans('Request Date')</th>
                                @if($status !== 'pending')
                                    <th class="px-6 py-4">@trans('Processed')</th>
                                @endif
                                @if($status === 'rejected')
                                    <th class="px-6 py-4">@trans('Reason')</th>
                                @endif
                                <th class="px-6 py-4 text-right">@trans('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @foreach($requests as $request)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">
                                            <a href="{{ route('admin.stores.show', $request->seller_id) }}" class="hover:text-blue-600 hover:underline">
                                                {{ $request->seller->sellerProfile->store_name ?? $request->seller->name }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-stone-500 mt-0.5">{{ $request->seller->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">${{ number_format($request->requested_amount, 2) }}</div>
                                    </td>
                                    @if($status === 'pending')
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-blue-600">${{ number_format($request->current_pending, 2) }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-stone-900">{{ $request->requested_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-stone-500">{{ $request->requested_at->format('h:i A') }}</div>
                                    </td>
                                    @if($status !== 'pending')
                                        <td class="px-6 py-4">
                                            @if($request->processedBy)
                                                <div class="text-sm text-stone-900">{{ $request->processedBy->name }}</div>
                                                <div class="text-xs text-stone-500">{{ $request->processed_at->format('M d, Y') }}</div>
                                            @else
                                                <span class="text-xs text-stone-400">@trans('N/A')</span>
                                            @endif
                                        </td>
                                    @endif
                                    @if($status === 'rejected')
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-red-900 max-w-xs truncate" title="{{ $request->admin_notes }}">
                                                {{ $request->admin_notes }}
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 text-right">
                                        @if($status === 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.payouts.index', ['seller_id' => $request->seller_id, 'payout_status' => 'pending']) }}"
                                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm">@trans('Review')</a>
                                                <button onclick="openRejectModal({{ $request->id }}, '{{ $request->seller->sellerProfile->store_name ?? $request->seller->name }}')"
                                                    class="text-red-600 hover:text-red-800 font-medium text-sm">@trans('Reject')</button>
                                            </div>
                                        @else
                                            <span class="text-xs text-stone-400">@trans('No actions')</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->appends(['status' => $status])->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-stone-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-stone-900 font-bold">@trans('No requests')</h3>
                <p class="text-stone-500 text-sm">@trans('There are no ' . $status . ' payout requests.')</p>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal"
        class="hidden fixed inset-0 bg-stone-900 bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform transition-all scale-100">
            <div class="p-6 border-b border-stone-100 flex justify-between items-center bg-red-50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-red-900">@trans('Reject Payout Request')</h3>
                <button onclick="closeRejectModal()" class="text-stone-400 hover:text-stone-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">@trans('Store Name')</label>
                        <p id="modalStoreName" class="text-lg font-bold text-stone-900"></p>
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-sm font-bold text-stone-700 mb-2">
                            @trans('Rejection Reason') <span class="text-stone-400 font-normal">(@trans('Optional'))</span>
                        </label>
                        <textarea name="admin_notes" id="admin_notes" rows="4"
                            class="w-full rounded-xl border-stone-200 focus:border-red-500 focus:ring-red-500"
                            placeholder="Provide a reason for rejection..."></textarea>
                        <p class="text-xs text-stone-500 mt-1">@trans('This will be visible to the seller')</p>
                    </div>
                </div>

                <div class="p-6 bg-stone-50 rounded-b-2xl border-t border-stone-100 flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-5 py-2.5 rounded-xl text-stone-600 font-bold hover:bg-stone-200 transition-colors">@trans('Cancel')</button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-200">
                        @trans('Reject Request')
                    </button>
                </div>
            </form>
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
        function openRejectModal(requestId, storeName) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/payout-requests/${requestId}/reject`;

            document.getElementById('modalStoreName').textContent = storeName;
            document.getElementById('admin_notes').value = '';

            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endsection