@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6 pb-24 md:pb-6"> {{-- Added padding-bottom for mobile nav --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-stone-900">@trans('Orders Management')</h1>
        
        {{-- Mobile Scrollable Tabs --}}
        <div class="w-full md:w-auto overflow-x-auto pb-2 -mx-4 px-4 md:mx-0 md:px-0 md:pb-0 scrollbar-hide">
            <div class="flex gap-2 min-w-max">
                <a href="{{ route('seller.orders.index') }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ $status == 'all' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-stone-600 border border-stone-200' }}">
                   @trans('All')
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ $status == 'pending' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-stone-600 border border-stone-200' }}">
                   @trans('Pending')
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'accepted']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ $status == 'accepted' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-stone-600 border border-stone-200' }}">
                   @trans('Accepted')
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'handed_to_courier']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ $status == 'handed_to_courier' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-stone-600 border border-stone-200' }}">
                   @trans('On Way')
                </a>
                <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ $status == 'completed' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-stone-600 border border-stone-200' }}">
                   @trans('Completed')
                </a>
            </div>
        </div>
    </div>

    @if($orders->count() > 0)
        {{-- Desktop Table View --}}
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden border border-stone-200">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Order')</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Product')</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Customer')</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Total')</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Status')</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">@trans('Actions')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-stone-900">
                                <a href="{{ route('seller.orders.show', $order) }}" class="hover:text-emerald-600 hover:underline transition-colors">
                                    #{{ $order->order_number }}
                                </a>
                                <div class="text-xs text-stone-400 font-normal">{{ $order->created_at->format('M d, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-stone-100 rounded overflow-hidden">
                                        <img class="h-10 w-10 object-cover" src="{{ $order->product->thumbnail ? asset('storage/' . $order->product->thumbnail) : 'https://placehold.co/100' }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-stone-900">{{ $order->product->name }}</div>
                                        <div class="text-xs text-stone-500">Qty: {{ $order->quantity }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-stone-900">{{ $order->buyer->name }}</div>
                                <div class="text-xs text-stone-500">{{ $order->buyer->phone }}</div>
                                <div class="text-xs text-stone-400 truncate max-w-[150px]" title="{{ $order->delivery_address }}">{{ $order->delivery_address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-0.5">
                                    <div class="text-xs text-stone-500">Base: ${{ number_format($order->product->price * $order->quantity, 2) }}</div>
                                    <div class="text-xs text-stone-400">Fee: ${{ number_format(($order->total_price - ($order->product->price * $order->quantity)), 2) }}</div>
                                    <div class="text-sm font-bold text-emerald-600">Final: ${{ number_format($order->total_price, 2) }}</div>
                                </div>
                                <div class="text-xs text-stone-500 mt-1">{{ $order->payment_method }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'handed_to_courier' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    @if($order->status === 'pending')
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded-md border border-green-200 hover:bg-green-100 transition-colors">@trans('Accept')</button>
                                        </form>
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md border border-red-200 hover:bg-red-100 transition-colors">@trans('Reject')</button>
                                        </form>
                                    @endif

                                    @if($order->status === 'accepted')
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="handed_to_courier">
                                            <button type="submit" class="text-purple-600 hover:text-purple-900 bg-purple-50 px-3 py-1 rounded-md border border-purple-200 hover:bg-purple-100 transition-colors">@trans('Courier')</button>
                                        </form>
                                    @endif
                                    
                                     @if($order->status === 'handed_to_courier')
                                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-3 py-1 rounded-md border border-emerald-200 hover:bg-emerald-100 transition-colors">@trans('Mark Completed')</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List View --}}
        <div class="md:hidden space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4">
                    {{-- Header: Order #, Date, Status --}}
                    <div class="flex justify-between items-start mb-3 border-b border-stone-50 pb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-stone-900">#{{ $order->order_number }}</h3>
                                <div class="text-xs text-stone-400">{{ $order->created_at->format('M d, H:i') }}</div>
                            </div>
                            <div class="text-xs text-stone-500 mt-1">{{ $order->buyer->name }}</div>
                        </div>
                        <span class="px-2 py-1 text-xs font-bold rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'handed_to_courier' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    {{-- Product Details --}}
                    <a href="{{ route('seller.orders.show', $order) }}" class="flex gap-3 mb-3">
                        <div class="flex-shrink-0 h-16 w-16 bg-stone-100 rounded-lg overflow-hidden">
                            <img class="h-full w-full object-cover" src="{{ $order->product->thumbnail ? asset('storage/' . $order->product->thumbnail) : 'https://placehold.co/100' }}">
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-stone-900 line-clamp-1">{{ $order->product->name }}</h4>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-xs text-stone-500">Qty: {{ $order->quantity }}</span>
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-bold text-emerald-600">${{ number_format($order->total_price, 2) }}</span>
                                    <span class="text-[10px] text-stone-500">Base: ${{ number_format($order->product->price * $order->quantity, 2) }}</span>
                                    <span class="text-[10px] text-stone-400">Fee: ${{ number_format($order->platform_fee, 2) }}</span>
                                </div>
                            </div>
                            <div class="text-xs text-stone-400 mt-1">{{ $order->payment_method }}</div>
                        </div>
                    </a>

                    {{-- Actions --}}
                    <div class="flex gap-2 mt-3 pt-3 border-t border-stone-50">
                        @if($order->status === 'pending')
                            <form action="{{ route('seller.orders.update-status', $order) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="w-full text-center text-sm font-bold text-green-700 bg-green-50 py-2 rounded-lg active:bg-green-100">@trans('Accept')</button>
                            </form>
                            <form action="{{ route('seller.orders.update-status', $order) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full text-center text-sm font-bold text-red-700 bg-red-50 py-2 rounded-lg active:bg-red-100">@trans('Reject')</button>
                            </form>
                        @elseif($order->status === 'accepted')
                            <form action="{{ route('seller.orders.update-status', $order) }}" method="POST" class="w-full">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="handed_to_courier">
                                <button type="submit" class="w-full text-center text-sm font-bold text-purple-700 bg-purple-50 py-2 rounded-lg active:bg-purple-100">@trans('Hand to Courier')</button>
                            </form>
                        @elseif($order->status === 'handed_to_courier')
                             <form action="{{ route('seller.orders.update-status', $order) }}" method="POST" class="w-full">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="w-full text-center text-sm font-bold text-emerald-700 bg-emerald-50 py-2 rounded-lg active:bg-emerald-100">@trans('Mark Completed')</button>
                            </form>
                        @else
                            <a href="{{ route('seller.orders.show', $order) }}" class="w-full text-center text-sm font-bold text-stone-600 bg-stone-100 py-2 rounded-lg active:bg-stone-200">@trans('View Details')</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $orders->appends(['status' => $status])->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-stone-200 mx-4 md:mx-0">
            <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="text-lg font-medium text-stone-900">@trans('No orders found')</h3>
            <p class="text-stone-500 mt-1">Status: {{ ucfirst($status) }}</p>
        </div>
    @endif
</div>
@endsection