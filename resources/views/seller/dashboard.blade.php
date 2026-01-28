@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 pb-24 md:pb-6">
    <div class="min-h-screen bg-gray-50 pb-24 md:pb-6">
        
        @if(auth()->user()->status !== 'active')
            {{-- Account Status Warning Banner --}}
            <div class="bg-{{ auth()->user()->status === 'pending' ? 'yellow' : 'red' }}-50 border-l-4 border-{{ auth()->user()->status === 'pending' ? 'yellow' : 'red' }}-500 p-6 mx-4 mt-4 md:mx-auto md:max-w-7xl rounded-lg">
                <div class="flex items-start">
                    <svg class="w-8 h-8 text-{{ auth()->user()->status === 'pending' ? 'yellow' : 'red' }}-500 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-{{ auth()->user()->status === 'pending' ? 'yellow' : 'red' }}-800 mb-2">
                            @if(auth()->user()->status === 'pending')
                                Account Pending Approval
                            @else
                                Account {{ ucfirst(auth()->user()->status) }}
                            @endif
                        </h3>
                        <p class="text-{{ auth()->user()->status === 'pending' ? 'yellow' : 'red' }}-700 mb-4">
                            @if(auth()->user()->status === 'pending')
                                Your seller account is currently under review by our admin team. You will be notified once your account is approved. In the meantime, you cannot access seller features.
                            @else
                                Your seller account has been {{ auth()->user()->status }}. You do not have access to seller features. Please contact the administrator for more information.
                            @endif
                        </p>
                        <div class="text-sm text-{{ auth()->user()->status === 'pending' ? 'yellow' : 'red' }}-600 font-medium">
                            <p>@trans('Status:') <span class="font-bold uppercase">{{ auth()->user()->status }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Desktop Header (Kept simple) -->
        @if(auth()->user()->status === 'active')
            <div class="hidden md:block bg-white border-b border-stone-200 px-6 py-8">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-stone-900">{{ $storeName }} Dashboard</h1>
                        <p class="text-stone-600 mt-1">@trans('Manage your store and track your performance')</p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 py-4 md:px-6 md:py-8 space-y-4">

                <!-- PWA Stats Cards (Compact Grid) -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    
                    <!-- Total Products (Blue) -->
                    <div class="bg-[#2563eb] rounded-2xl p-4 text-white relative overflow-hidden shadow-sm flex flex-col justify-between h-32">
                        <div class="z-10 relative">
                            <p class="text-blue-100 text-[10px] font-bold tracking-wider uppercase mb-0.5">@trans('Products')</p>
                            <p class="text-3xl font-bold">{{ $productsCount }}</p>
                        </div>
                        <a href="{{ route('seller.products.index') }}" class="flex items-center text-xs font-medium text-blue-50 hover:text-white group z-10 relative mt-auto">
                            @trans('View All') 
                            <svg class="w-3 h-3 ml-0.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <div class="absolute -right-2 -bottom-4 bg-white/10 p-3 rounded-full rotate-12">
                            <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                    </div>

                    <!-- Pending Orders (Orange) -->
                    <div class="bg-[#f97316] rounded-2xl p-4 text-white relative overflow-hidden shadow-sm flex flex-col justify-between h-32">
                        <div class="z-10 relative">
                            <p class="text-orange-100 text-[10px] font-bold tracking-wider uppercase mb-0.5">@trans('Pending')</p>
                            <p class="text-3xl font-bold">{{ $pendingOrders }}</p>
                        </div>
                        <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}" class="flex items-center text-xs font-medium text-orange-50 hover:text-white group z-10 relative mt-auto">
                            @trans('Orders')
                            <svg class="w-3 h-3 ml-0.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <div class="absolute -right-2 -bottom-4 bg-white/10 p-3 rounded-full rotate-12">
                            <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Total Earnings (Green) -->
                    <div class="col-span-2 md:col-span-1 bg-[#10b981] rounded-2xl p-4 text-white relative overflow-hidden shadow-sm flex flex-col justify-between h-32">
                        <div class="z-10 relative">
                            <p class="text-emerald-100 text-[10px] font-bold tracking-wider uppercase mb-0.5">@trans('Total Earnings')</p>
                             <p class="text-3xl font-bold tracking-tight">${{ number_format($earnings, 0) }}<span class="text-lg opacity-80">.{{ explode('.', number_format($earnings, 2))[1] }}</span></p>
                        </div>
                        <a href="{{ route('seller.earnings') }}" class="flex items-center text-xs font-medium text-emerald-50 hover:text-white group z-10 relative mt-auto">
                            @trans('Details')
                            <svg class="w-3 h-3 ml-0.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                         <div class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 p-3 rounded-full">
                            <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-stone-900 mb-4">@trans('Quick Actions')</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <a href="{{ route('seller.products.create') }}"
                            class="flex flex-col items-center justify-center p-4 bg-emerald-50 hover:bg-emerald-100 rounded-xl border-2 border-emerald-200 hover:border-emerald-300 transition-all group">
                            <div class="bg-emerald-500 p-3 rounded-full mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-stone-700">@trans('Add Product')</span>
                        </a>

                        <a href="{{ route('seller.orders.index') }}"
                            class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl border-2 border-blue-200 hover:border-blue-300 transition-all group">
                            <div class="bg-blue-500 p-3 rounded-full mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-stone-700">@trans('View Orders')</span>
                        </a>

                        <a href="{{ route('seller.products.index') }}"
                            class="flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl border-2 border-purple-200 hover:border-purple-300 transition-all group">
                            <div class="bg-purple-500 p-3 rounded-full mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-stone-700">@trans('My Products')</span>
                        </a>

                        <a href="{{ route('seller.earnings') }}"
                            class="flex flex-col items-center justify-center p-4 bg-amber-50 hover:bg-amber-100 rounded-xl border-2 border-amber-200 hover:border-amber-300 transition-all group">
                            <div class="bg-amber-500 p-3 rounded-full mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-stone-700">@trans('Analytics')</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center bg-stone-50/50">
                        <h2 class="text-xl font-bold text-stone-900">@trans('Recent Orders')</h2>
                        <a href="{{ route('seller.orders.index') }}"
                            class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">@trans('View All')</a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="divide-y divide-stone-100">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('seller.orders.show', $order) }}"
                                    class="block p-4 hover:bg-stone-50 transition-colors group">
                                    <div class="flex items-center gap-4">
                                        {{-- Product Image --}}
                                        <div
                                            class="relative h-16 w-16 flex-shrink-0 bg-stone-100 rounded-lg overflow-hidden border border-stone-200">
                                            <img src="{{ $order->product->thumbnail ? asset('storage/' . $order->product->thumbnail) : 'https://placehold.co/100' }}"
                                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>

                                        {{-- Order Details --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="font-bold text-stone-900 truncate pr-2">{{ $order->product->name }}</h3>
                                                    <p class="text-xs text-stone-500 mt-0.5">Order #{{ $order->order_number }} •
                                                        {{ $order->created_at->diffForHumans() }}</p>
                                                </div>
                                                <span class="flex-shrink-0 px-2.5 py-1 text-xs font-bold rounded-full
                                                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                {{ $order->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                {{ $order->status === 'handed_to_courier' ? 'bg-purple-100 text-purple-800' : '' }}
                                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                                {{ $order->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>

                                            <div class="flex justify-between items-center mt-2">
                                                <div class="flex items-center gap-2 text-xs text-stone-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                            </path>
                                                        </svg>
                                                        {{ $order->buyer->name }}
                                                    </span>
                                                    <span>•</span>
                                                    <span class="font-medium text-stone-900">Qty: {{ $order->quantity }}</span>
                                                </div>
                                                <span
                                                    class="text-sm font-bold text-emerald-600">${{ number_format($order->total_price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="p-3 text-center border-t border-stone-100 md:hidden">
                            <a href="{{ route('seller.orders.index') }}" class="text-sm font-bold text-stone-600">See All Orders
                                &rarr;</a>
                        </div>
                    @else
                        <div class="text-center py-12 px-6">
                            <div class="h-16 w-16 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-stone-900">@trans('No orders yet')</h3>
                            <p class="text-stone-500 text-sm mt-1 mb-4">@trans('Your recent orders will show up here once you start selling.')
                            </p>
                            <a href="{{ route('seller.products.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition-colors">
                                @trans('Add First Product')
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        </div>
    </div>
@endsection