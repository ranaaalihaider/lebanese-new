@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-stone-50 to-stone-100 pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-stone-200 px-4 md:px-6 py-6 md:py-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl md:text-3xl font-bold text-stone-900">Admin Dashboard</h1>
                <p class="text-stone-600 text-sm md:text-base mt-1">Platform Overview & System Management</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 md:px-6 md:py-8">
            <!-- System Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-2xl border border-amber-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-amber-600 uppercase mb-1">Pending Approvals</p>
                            <p class="text-3xl md:text-4xl font-bold text-amber-900">{{ $pendingSellers }}</p>
                        </div>
                        <div class="bg-amber-500 p-3 rounded-full shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-2xl border border-emerald-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase mb-1">Active Sellers</p>
                            <p class="text-3xl md:text-4xl font-bold text-emerald-900">{{ $totalSellers }}</p>
                        </div>
                        <div class="bg-emerald-500 p-3 rounded-full shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total Buyers</p>
                            <p class="text-3xl md:text-4xl font-bold text-blue-900">{{ $totalBuyers }}</p>
                        </div>
                        <div class="bg-blue-500 p-3 rounded-full shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-stone-900 mb-5">Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 md:gap-4">
                    <a href="{{ route('admin.sellers') }}"
                        class="group flex flex-col items-center p-4 md:p-5 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl border-2 border-blue-200 hover:border-blue-300 transition-all">
                        <div class="bg-blue-500 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-700 text-center">Sellers</span>
                    </a>

                    <a href="{{ route('admin.buyers') }}"
                        class="group flex flex-col items-center p-4 md:p-5 bg-gradient-to-br from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 rounded-xl border-2 border-indigo-200 hover:border-indigo-300 transition-all">
                        <div
                            class="bg-indigo-500 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-700 text-center">Buyers</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="group flex flex-col items-center p-4 md:p-5 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl border-2 border-purple-200 hover:border-purple-300 transition-all">
                        <div
                            class="bg-purple-500 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-700 text-center">Products</span>
                    </a>

                    <a href="{{ route('admin.lists') }}"
                        class="group flex flex-col items-center p-4 md:p-5 bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 rounded-xl border-2 border-orange-200 hover:border-orange-300 transition-all">
                        <div
                            class="bg-orange-500 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-700 text-center">Lists</span>
                    </a>

                    <a href="{{ route('admin.earnings') }}"
                        class="group flex flex-col items-center p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 rounded-xl border-2 border-emerald-200 hover:border-emerald-300 transition-all">
                        <div
                            class="bg-emerald-500 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-700 text-center">Revenue</span>
                    </a>

                    <a href="{{ route('admin.settings') }}"
                        class="group flex flex-col items-center p-4 md:p-5 bg-gradient-to-br from-stone-50 to-stone-100 hover:from-stone-100 hover:to-stone-200 rounded-xl border-2 border-stone-200 hover:border-stone-300 transition-all">
                        <div
                            class="bg-stone-500 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-700 text-center">Settings</span>
                    </a>
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-xl font-bold text-stone-900 mb-5">System Health</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="font-semibold text-stone-900">Platform Status</span>
                        </div>
                        <span class="text-sm font-bold text-emerald-600">Online</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="font-semibold text-stone-900">Database Connection</span>
                        </div>
                        <span class="text-sm font-bold text-blue-600">Connected</span>
                    </div>

                    @if($pendingSellers > 0)
                        <div class="flex items-center justify-between p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                                <span class="font-semibold text-stone-900">Pending Actions</span>
                            </div>
                            <a href="{{ route('admin.sellers', ['status' => 'pending']) }}"
                                class="text-sm font-bold text-amber-600 hover:text-amber-700 hover:underline">
                                {{ $pendingSellers }} seller{{ $pendingSellers > 1 ? 's' : '' }} awaiting approval
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    @include('components.bottom-nav')
@endsection