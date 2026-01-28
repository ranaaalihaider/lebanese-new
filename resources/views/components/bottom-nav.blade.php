@auth
    @if(Auth::user()->role === 'admin')
        <!-- Admin Bottom Nav -->
        <div
            class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-100 flex items-center h-20 z-50 pb-safe shadow-[0_-5px_40px_rgba(0,0,0,0.08)] transition-all duration-300 md:hidden overflow-x-auto px-2 gap-4 no-scrollbar">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Dashboard')</span>
            </a>

            <!-- Orders -->
            <a href="{{ route('admin.orders.index') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] {{ request()->routeIs('admin.orders*') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Orders')</span>
            </a>

            <!-- Sellers -->
            <a href="{{ route('admin.sellers') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] {{ request()->routeIs('admin.sellers*') || request()->routeIs('admin.stores*') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Sellers')</span>
            </a>

            <!-- Buyers -->
            <a href="{{ route('admin.buyers') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] {{ request()->routeIs('admin.buyers*') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Buyers')</span>
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products.index') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] {{ request()->routeIs('admin.products*') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Products')</span>
            </a>

            <!-- Revenue -->
            <a href="{{ route('admin.earnings') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] {{ request()->routeIs('admin.earnings') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Revenue')</span>
            </a>

            <!-- Payouts -->
            <a href="{{ route('admin.payouts.index') }}"
                class="flex flex-col items-center justify-center space-y-1 px-2 py-2 transition-all duration-200 group flex-shrink-0 min-w-[70px] pr-4 {{ request()->routeIs('admin.payouts*') ? 'text-emerald-600' : 'text-stone-500' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-transform group-active:scale-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-bold whitespace-nowrap">@trans('Payouts')</span>
            </a>
        </div>

    @elseif(Auth::user()->role === 'seller')
        <nav
            class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-100 flex justify-around items-center h-20 z-50 pb-safe shadow-[0_-5px_40px_rgba(0,0,0,0.08)] transition-all duration-300 md:hidden">

            <!-- Dashboard -->
            <a href="{{ route('seller.dashboard') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('seller.dashboard') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('seller.dashboard') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('seller.dashboard') ? '0' : '2' }}"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('Status')</span>
            </a>

            <!-- Orders -->
            <a href="{{ route('seller.orders.index') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('seller.orders*') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('seller.orders*') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('seller.orders*') ? '0' : '2' }}"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('Orders')</span>
            </a>

            <!-- Products -->
            <a href="{{ route('seller.products.index') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('seller.products.index') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5"
                        fill="{{ request()->routeIs('seller.products.index') ? 'currentColor' : 'none' }}" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('seller.products.index') ? '0' : '2' }}"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('Products')</span>
            </a>

            <!-- Earnings -->
            <a href="{{ route('seller.earnings') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('seller.earnings') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('seller.earnings') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('seller.earnings') ? '0' : '2' }}"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('Earnings')</span>
            </a>

        </nav>
    @else
        {{-- Default buyer bottom nav --}}
        <nav
            class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-100 flex justify-around items-center h-20 z-50 pb-safe shadow-[0_-5px_40px_rgba(0,0,0,0.08)] transition-all duration-300 md:hidden">
            <a href="{{ route('buyer.home') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('buyer.home') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('buyer.home') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('buyer.home') ? '0' : '2' }}"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('Home')</span>
            </a>

            <a href="{{ route('buyer.stores') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('buyer.stores') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('buyer.stores') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('buyer.stores') ? '0' : '2' }}"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('Stores')</span>
            </a>

            <a href="{{ route('buyer.orders.index') }}"
                class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('buyer.orders.index') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
                <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                    <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('buyer.orders.index') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('buyer.orders.index') ? '0' : '2' }}"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold tracking-wide">@trans('My Orders')</span>
            </a>
        </nav>
    @endif
@else
    {{-- Guest bottom nav --}}
    <nav
        class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-100 flex justify-around items-center h-20 z-50 pb-safe shadow-[0_-5px_40px_rgba(0,0,0,0.08)] transition-all duration-300 md:hidden">
        <a href="{{ route('buyer.home') }}"
            class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('buyer.home') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
            <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('buyer.home') ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="{{ request()->routeIs('buyer.home') ? '0' : '2' }}"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-wide">@trans('Home')</span>
        </a>

        <a href="{{ route('buyer.stores') }}"
            class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('buyer.stores') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
            <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                <svg class="w-7 h-7 mb-0.5" fill="{{ request()->routeIs('buyer.stores') ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="{{ request()->routeIs('buyer.stores') ? '0' : '2' }}"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-wide">@trans('Stores')</span>
        </a>

        <a href="{{ route('login') }}"
            class="flex flex-col items-center justify-center w-full h-full group {{ request()->routeIs('login') ? 'text-emerald-600' : 'text-stone-400 hover:text-stone-600' }}">
            <div class="relative p-1 transition-transform duration-200 group-active:scale-95">
                <svg class="w-7 h-7 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-wide">@trans('Login')</span>
        </a>
    </nav>
@endauth