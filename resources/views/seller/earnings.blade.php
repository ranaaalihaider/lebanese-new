@extends('layouts.app')

@section('content')
    <div class="p-4 md:p-6 pb-24 md:pb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-stone-900">@trans('My Earnings')</h1>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                {{-- Pending Payouts Box --}}
                <div
                    class="bg-orange-50 border border-orange-100 px-6 py-4 rounded-xl flex justify-between items-center shadow-sm w-full sm:w-auto">
                    <span class="text-orange-800 font-medium text-sm md:text-base">@trans('Pending Payout')</span>
                    <span
                        class="text-orange-700 text-2xl font-bold ml-2 border-l border-orange-200 pl-4">${{ number_format($pendingPayouts, 2) }}</span>
                </div>

                {{-- Total Earnings Box --}}
                <div
                    class="bg-emerald-50 border border-emerald-100 px-6 py-4 rounded-xl flex justify-between items-center shadow-sm w-full sm:w-auto">
                    <span class="text-emerald-800 font-medium text-sm md:text-base">@trans('Total Net Income')</span>
                    <span
                        class="text-emerald-700 text-2xl font-bold ml-2 border-l border-emerald-200 pl-4">${{ number_format($totalEarnings, 2) }}</span>
                </div>

                {{-- Request Payout Button --}}
                @if($pendingPayouts > 0)
                    @if($payoutRequest)
                        <div
                            class="bg-blue-50 border border-blue-200 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm w-full sm:w-auto">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-blue-600 font-medium">@trans('Request Pending')</p>
                                <p class="text-xs text-blue-500">{{ $payoutRequest->requested_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('seller.payout.request') }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full px-6 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-bold rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                @trans('Request Payout')
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>


        {{-- Search and Filter Form --}}
        <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-stone-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-stone-900">@trans('Filters')</h2>
                <button onclick="toggleEarningsFilters()"
                    class="px-4 py-2 bg-stone-100 text-stone-600 rounded-xl flex items-center gap-2 hover:bg-stone-200 transition-colors text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    @trans('Toggle Filters')
                </button>
            </div>

            <div id="earnings-filters-container"
                class="{{ request()->anyFilled(['search', 'start_date', 'end_date', 'payout_status']) ? '' : 'hidden' }}">
                <form action="{{ route('seller.earnings') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="sr-only">@trans('Search')</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search"
                                class="block w-full pl-10 pr-3 py-2 border border-stone-200 rounded-lg leading-5 bg-white placeholder-stone-500 focus:outline-none focus:placeholder-stone-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                placeholder="Order # or Product" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                        <div class="w-full md:w-36">
                            <label for="start_date" class="sr-only">@trans('Start Date')</label>
                            <input type="date" name="start_date" id="start_date"
                                class="block w-full px-3 py-2 border border-stone-200 rounded-lg leading-5 bg-white placeholder-stone-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="w-full md:w-36">
                            <label for="end_date" class="sr-only">@trans('End Date')</label>
                            <input type="date" name="end_date" id="end_date"
                                class="block w-full px-3 py-2 border border-stone-200 rounded-lg leading-5 bg-white placeholder-stone-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="w-full md:w-40">
                        <label for="payout_status" class="sr-only">@trans('Payout Status')</label>
                        <div class="relative">
                            <select name="payout_status" id="payout_status" onchange="this.form.submit()"
                                class="block w-full pl-3 pr-10 py-2 text-base border-stone-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="all" {{ request('payout_status') == 'all' ? 'selected' : '' }}>
                                    @trans('All Statuses')
                                </option>
                                <option value="pending" {{ request('payout_status') == 'pending' ? 'selected' : '' }}>Pending
                                    Payout</option>
                                <option value="paid" {{ request('payout_status') == 'paid' ? 'selected' : '' }}>@trans('Paid')
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button type="submit"
                            class="w-full md:w-auto px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            @trans('Filter')
                        </button>
                        @if(request()->has('search') || (request()->has('payout_status') && request('payout_status') !== 'all'))
                            <a href="{{ route('seller.earnings') }}"
                                class="ml-2 text-sm text-stone-500 hover:text-stone-700">@trans('Clear')</a>
                        @endif
                    </div>
            </div>
            </form>
        </div>
        @if(request()->has('search') || (request()->has('payout_status') && request('payout_status') !== 'all'))
            <div class="mt-2 text-xs text-stone-500">
                Showing results for:
                @if(request('search'))
                    <span class="font-bold">"{{ request('search') }}"</span>
                @endif
                @if(request('search') && request('payout_status') !== 'all')
                    and
                @endif
                @if(request('payout_status') !== 'all')
                    Status: <span class="font-bold capitalize">{{ request('payout_status') }}</span>
                @endif
            </div>
        @endif
    </div>

    @if($orders->count() > 0)
        {{-- Desktop Table --}}
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden border border-stone-200">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                            @trans('Order Date')
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                            @trans('Order #')</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Item')
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                            @trans('Base Amount')
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                            @trans('Platform Fee')
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                            @trans('Final Amount')
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                            @trans('Payout Status')
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-600">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-stone-900">
                                <a href="{{ route('seller.orders.show', $order) }}"
                                    class="hover:text-emerald-600 hover:underline transition-colors">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                {{ $order->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600">
                                ${{ number_format($order->seller_earning, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-500">
                                +${{ number_format($order->platform_fee, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-600">
                                ${{ number_format($order->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                @if($order->payout_status === 'paid')
                                    <button onclick='openViewPayoutModal(@json($order))'
                                        class="text-stone-500 hover:text-emerald-600 transition-colors" title="View Payout Details">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        @trans('Pending')
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-3">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex flex-col">
                            <span class="text-xs text-stone-400 font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                            <a href="{{ route('seller.orders.show', $order) }}"
                                class="font-bold text-stone-800 hover:text-emerald-600">#{{ $order->order_number }}</a>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs text-stone-400 uppercase tracking-wider">@trans('Net Earned')</span>
                            <span class="text-xl font-bold text-emerald-600">+${{ number_format($order->seller_earning, 2) }}</span>
                        </div>
                    </div>
                    <div class="border-t border-dashed border-stone-100 my-2 pt-2">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-stone-500 truncate max-w-[60%]">{{ $order->product->name }}</span>
                            <span class="text-stone-900">${{ number_format($order->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-stone-400">@trans('Platform Fee')</span>
                            <span class="text-red-400">-${{ number_format($order->platform_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-stone-50">
                            <span class="text-xs text-stone-400">@trans('Payout Status')</span>
                            @if($order->payout_status === 'paid')
                                <button onclick='openViewPayoutModal(@json($order))'
                                    class="flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    @trans('View Details')
                                </button>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    @trans('Pending')
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-stone-200">
            <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
            <h3 class="text-lg font-medium text-stone-900">@trans('No earnings yet')</h3>
            <p class="text-stone-500 mt-1">@trans('Complete orders to see your earnings here.')</p>
        </div>
    @endif
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
                        <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Earned Amount')</p>
                        <p id="viewAmount" class="text-2xl font-bold text-emerald-600">$0.00</p>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Payment Method')
                        </p>
                        <p id="viewMethod" class="text-stone-900 font-medium"></p>
                    </div>

                    <div>
                        <p class="text-xs text-stone-500 uppercase font-bold tracking-wide mb-1">@trans('Transaction ID')
                        </p>
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
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        @trans('Received To Bank Account')
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
                    </div>
                </div>
            </div>

            <div class="p-6 bg-stone-50 rounded-b-2xl border-t border-stone-100 flex justify-end">
                <button onclick="closeViewPayoutModal()"
                    class="px-5 py-2.5 rounded-xl bg-stone-900 text-white font-bold hover:bg-stone-800 transition-colors shadow-lg shadow-stone-200">@trans('Close')</button>
            </div>
        </div>
    </div>

    <script>
        function openViewPayoutModal(order) {
            document.getElementById('viewOrderNumber').textContent = '#' + order.order_number;
            document.getElementById('viewAmount').textContent = '$' + parseFloat(order.seller_earning).toFixed(2);
            document.getElementById('viewMethod').textContent = order.payout_method;
            document.getElementById('viewTransactionId').textContent = order.payout_transaction_id;

            // Format Date
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

        function toggleEarningsFilters() {
            document.getElementById('earnings-filters-container').classList.toggle('hidden');
        }
    </script>
@endsection