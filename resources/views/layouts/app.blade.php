<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lebanese Marketplace') }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Favicon -->
    @php
        $logoPath = \App\Models\Setting::getSetting('site_logo');
    @endphp
    @if($logoPath)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $logoPath) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $logoPath) }}">
    @else
        <link rel="icon" type="image/png" href="/icon.png">
        <link rel="apple-touch-icon" href="/icon.png">
    @endif

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-stone-50 min-h-screen text-gray-800">

    <!-- Desktop Navigation (Hidden on Mobile) -->
    <nav class="hidden md:flex bg-emerald-800 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 w-full flex flex-wrap justify-between items-center gap-y-2 py-2 min-h-16">
            <!-- Logo -->
            @php
                $logoLink = route('buyer.home');
                if (Auth::check()) {
                    if (Auth::user()->role === 'admin') {
                        $logoLink = route('admin.dashboard');
                    } elseif (Auth::user()->role === 'seller') {
                        $logoLink = route('seller.dashboard');
                    }
                }
            @endphp
            <a href="{{ $logoLink }}" class="flex items-center gap-3">
                @php
                    $logoPath = \App\Models\Setting::getSetting('site_logo');
                    $siteName = \App\Models\Setting::getSetting('site_name') ?? 'Lebanese Marketplace';
                    $siteTagline = \App\Models\Setting::getSetting('site_tagline') ?? 'Your trusted marketplace';
                @endphp
                @if($logoPath)
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="h-10 object-contain">
                @else
                    <div class="h-10 w-10 bg-emerald-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ substr($siteName, 0, 1) }}</span>
                    </div>
                @endif
                <div class="flex flex-col">
                    <span class="text-lg font-bold tracking-tight leading-tight">{{ $siteName }}</span>
                    <span class="text-xs text-emerald-200 leading-tight">{{ $siteTagline }}</span>
                </div>
            </a>

            <!-- Install App Button (Desktop) -->
            <button id="pwa-install-btn-desktop" class="hidden items-center gap-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 px-3 py-1.5 rounded-lg transition-colors ml-4 mr-auto md:mr-0" style="display: none;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span class="text-sm font-semibold">@trans('Install App')</span>
            </button>

            <!-- Desktop Links - Wraps to second line on smaller screens -->
            <div class="flex items-center flex-wrap gap-x-3 md:gap-x-4 lg:gap-x-6 xl:gap-x-8 gap-y-2 flex-1 ml-6">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Dashboard')</a>
                        <a href="{{ route('admin.orders.index') }}"
                            class="{{ request()->routeIs('admin.orders*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Orders')</a>
                        <a href="{{ route('admin.sellers') }}"
                            class="{{ request()->routeIs('admin.sellers*') || request()->routeIs('admin.stores*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Sellers')</a>
                        <a href="{{ route('admin.buyers') }}"
                            class="{{ request()->routeIs('admin.buyers*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Buyers')</a>
                        <a href="{{ route('admin.products.index') }}"
                            class="{{ request()->routeIs('admin.products*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Products')</a>
                        <a href="{{ route('admin.earnings') }}"
                            class="{{ request()->routeIs('admin.earnings') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Revenue')</a>
                        <a href="{{ route('admin.payouts.index') }}"
                            class="{{ request()->routeIs('admin.payouts*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Payouts')</a>

                        <!-- Admin Dropdown for Management -->
                        <div class="relative group flex-shrink-0" x-data="{ open: false }">
                            <button @click="open = !open" @mouseenter="open = true"
                                class="flex items-center gap-1 text-emerald-100/80 hover:text-white transition-colors focus:outline-none text-sm md:text-base">
                                <span>@trans('Lists')</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" @mouseleave="open = false"
                                class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">
                                <a href="{{ route('admin.product-types.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@trans('Product Categories')</a>
                                <a href="{{ route('admin.store-types.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@trans('Store Categories')</a>
                            </div>
                        </div>

                    @elseif(Auth::user()->role == 'seller')
                        <a href="{{ route('seller.dashboard') }}"
                            class="{{ request()->routeIs('seller.dashboard') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Dashboard')</a>
                        <a href="{{ route('seller.products.index') }}"
                            class="{{ request()->routeIs('seller.products*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Products')</a>

                        <!-- Orders Dropdown -->
                        <div class="relative group flex-shrink-0" x-data="{ open: false }">
                            <button @click="open = !open" @mouseenter="open = true"
                                class="flex items-center gap-1 {{ request()->routeIs('seller.orders*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors focus:outline-none text-sm md:text-base">
                                <span>@trans('Orders')</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" @mouseleave="open = false"
                                class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">
                                <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@trans('Pending Orders')</a>
                                <a href="{{ route('seller.orders.index', ['status' => 'new']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@trans('New Orders')</a>
                                <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@trans('Completed Orders')</a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('seller.orders.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold">@trans('All Orders')</a>
                            </div>
                        </div>

                        <a href="{{ route('seller.earnings') }}"
                            class="{{ request()->routeIs('seller.earnings') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Earnings')</a>


                    @else
                        <!-- Buyer / Other Role -->
                        <a href="{{ route('buyer.home') }}"
                            class="{{ request()->routeIs('buyer.home') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">@trans('Home')</a>
                        <a href="{{ route('buyer.orders.index') }}"
                            class="{{ request()->routeIs('buyer.orders*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">My
                            Orders</a>
                        <a href="{{ route('buyer.wishlist.index') }}"
                            class="{{ request()->routeIs('buyer.wishlist*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">@trans('Wishlist')</a>
                        <a href="{{ route('buyer.stores') }}"
                            class="{{ request()->routeIs('buyer.stores') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">@trans('Stores')</a>
                        <a href="{{ route('about') }}" class="text-emerald-100/80 hover:text-white transition-colors">About
                            Us</a>
                    @endif
                @else
                    <!-- Guest -->
                    <a href="{{ route('buyer.home') }}"
                        class="{{ request()->routeIs('buyer.home') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">@trans('Home')</a>
                    <a href="{{ route('buyer.stores') }}"
                        class="{{ request()->routeIs('buyer.stores') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">@trans('Stores')</a>
                    <a href="{{ route('about') }}" class="text-emerald-100/80 hover:text-white transition-colors">About
                        Us</a>
                @endauth
            </div>

            <!-- Right Side (Profile) -->
            <div class="flex items-center gap-4">
                <!-- Language Switcher Removed (Moved to User Dropdown) -->

                <!-- Notifications Dropdown -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-emerald-100 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full"
                                  style="{{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'display: none;' }}">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-stone-200 overflow-hidden z-50 py-1"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-stone-100 bg-stone-50 flex justify-between items-center">
                                <span class="font-bold text-stone-700 text-sm">@trans('Notifications')</span>
                                <span class="text-xs text-stone-500">{{ Auth::user()->unreadNotifications->count() }} @trans('Unread')</span>
                            </div>
                            
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(Auth::user()->notifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-stone-50 transition-colors border-b border-stone-100 {{ $notification->read_at ? 'opacity-60' : '' }}">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="w-2 h-2 rounded-full {{ $notification->read_at ? 'bg-stone-300' : 'bg-emerald-500' }}"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-stone-800">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                                <p class="text-xs text-stone-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-stone-500 text-sm">
                                        @trans('No notifications')
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- Cart Icon (Buyer Only) -->
                @auth
                    @if(Auth::user()->role === 'buyer')
                        <a href="{{ route('buyer.cart.index') }}"
                            class="relative p-2 text-emerald-100 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            @if(Auth::user()->carts()->count() > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">{{ Auth::user()->carts()->count() }}</span>
                            @endif
                        </a>
                    @endif
                @endauth

                @auth
                    <div class="relative ml-3" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button"
                                class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-emerald-800 focus:ring-white"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">@trans('Open user menu')</span>
                                <div
                                    class="h-8 w-8 rounded-full bg-emerald-700 flex items-center justify-center text-white font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                            style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm text-gray-900 font-bold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>

                            <!-- Language Selection -->
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    @trans('Language')</p>
                                <div class="space-y-1">
                                    @foreach(\App\Helpers\LanguageHelper::getLanguages() as $code => $detail)
                                        <a href="{{ route('lang.switch', $code) }}"
                                            class="flex items-center justify-between text-sm text-gray-700 hover:text-emerald-600 hover:bg-gray-50 rounded px-2 py-1 transition-colors">
                                            <span class="flex items-center gap-2">
                                                <span class="text-lg">{{ $detail['flag'] }}</span>
                                                {{ $detail['name'] }}
                                            </span>
                                            @if(app()->getLocale() == $code)
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.settings') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">@trans('Settings')</a>
                            @else
                                <a href="{{ Auth::user()->role === 'buyer' ? route('buyer.orders.index') : route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    {{ Auth::user()->role === 'buyer' ? 'My Orders' : 'Visit Account' }}
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem">@trans('Profile')</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">@trans('Log out')</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Profile / Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 bg-emerald-800/50 hover:bg-emerald-800 rounded-full pl-3 pr-2 py-1.5 transition-colors">
                            <span class="text-white text-sm font-semibold">@trans('Guest')</span>
                            <div
                                class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-stone-200 overflow-hidden z-50"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-stone-100 bg-stone-50">
                                <p class="text-sm font-bold text-stone-900">@trans('Welcome, Guest')</p>
                                <p class="text-xs text-stone-500">@trans('Sign in to manage orders')</p>
                            </div>

                            <!-- Language Selection -->
                            <div class="px-4 py-2 border-b border-stone-100">
                                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2">
                                    @trans('Language')</p>
                                <div class="space-y-1">
                                    @foreach(\App\Helpers\LanguageHelper::getLanguages() as $code => $detail)
                                        <a href="{{ route('lang.switch', $code) }}"
                                            class="flex items-center justify-between text-sm text-stone-700 hover:text-emerald-600 hover:bg-emerald-50 rounded px-2 py-1 transition-colors">
                                            <span class="flex items-center gap-2">
                                                <span class="text-lg">{{ $detail['flag'] }}</span>
                                                {{ $detail['name'] }}
                                            </span>
                                            @if(app()->getLocale() == $code)
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-2">
                                <a href="{{ route('login') }}"
                                    class="flex items-center justify-center gap-2 w-full bg-emerald-600 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-emerald-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    @trans('Login / Register')
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile PWA Top Header (Auto-included for authenticated users) -->
    @include('components.top-header')

    <!-- Main Content Container -->
    <div id="app" class="w-full min-h-screen relative pb-24 pb-safe md:pb-0">

        <!-- Sticky Header (Only show if not authenticated or if desired) -->
        {{-- We can make this conditional or part of specific views. For now, let's keep it simple. --}}

        @if(session('otp_message'))
            <div class="fixed top-0 inset-x-0 z-50 bg-green-100 border-b border-green-400 text-green-700 px-4 py-3 text-center"
                role="alert">
                <strong class="font-bold">@trans('OTP:')</strong>
                <span class="block sm:inline">{{ session('otp_message') }}</span>
            </div>
        @endif

        <main class="relative z-0">
            @yield('content')
        </main>
    </div>

    <!-- Seller Support Button -->
    @if(auth()->check() && auth()->user()->role === 'seller')
        @php
            $supportNumber = \App\Models\Setting::getSetting('whatsapp_number');
            $supportNumberClean = preg_replace('/[^0-9]/', '', $supportNumber);
            $storeName = auth()->user()->sellerProfile->store_name ?? 'My Store';
            $message = "Hello, I need support for my store: " . $storeName;
            $whatsappLink = "https://wa.me/{$supportNumberClean}?text=" . urlencode($message);
        @endphp

        @if($supportNumber)
            <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                class="fixed bottom-24 right-4 md:bottom-8 md:right-8 z-50 bg-[#25D366] hover:bg-[#20bd5a] text-white p-3 rounded-full shadow-lg transition-transform hover:scale-110 flex items-center justify-center group"
                style="text-decoration: none;" title="@trans('Contact Support')">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                <span
                    class="absolute right-full mr-3 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                    @trans('Seller Support')
                </span>
            </a>
        @endif
    @endif

    <!-- Mobile PWA Bottom Navigation (Auto-included for authenticated users) -->
    @include('components.bottom-nav')

    <script>
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => console.log('SW registered'))
                    .catch(err => console.log('SW failed', err));
            });
        }

        // PWA Install Logic
        let deferredPrompt;
        const installBtns = document.querySelectorAll('#pwa-install-btn-desktop, #pwa-install-btn-mobile');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            installBtns.forEach(btn => {
                btn.style.display = 'flex'; // Show button
                btn.classList.remove('hidden');
            });

            installBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    // hide our user interface that shows our A2HS button
                    btn.style.display = 'none';
                    // Show the prompt
                    deferredPrompt.prompt();
                    // Wait for the user to respond to the prompt
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        deferredPrompt = null;
                    });
                });
            });
        });

        // Hide button if app is installed
        window.addEventListener('appinstalled', (evt) => {
            installBtns.forEach(btn => {
                btn.style.display = 'none';
            });
        });
    </script>
    @stack('scripts')
    <!-- Spacer for fixed header (mobile only) -->
    <div class="h-14 md:hidden"></div>

@auth
    <div id="notification-permission-banner" class="fixed bottom-0 inset-x-0 pb-safe z-50 transform translate-y-full transition-transform duration-300" style="display: none;">
        <div class="bg-emerald-600 p-4 shadow-lg">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="p-2 bg-emerald-500 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </span>
                    <p class="font-medium text-white text-sm md:text-base">
                        @trans('Enable push notifications to get instant order updates.')
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button id="dismiss-notification-banner" class="text-emerald-100 hover:text-white text-sm">@trans('Later')</button>
                    <button id="enable-notifications-btn" class="bg-white text-emerald-600 px-4 py-2 rounded-lg font-bold text-sm shadow-sm hover:bg-emerald-50 transition-colors">
                        @trans('Enable')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const banner = document.getElementById('notification-permission-banner');
            const enableBtn = document.getElementById('enable-notifications-btn');
            const dismissBtn = document.getElementById('dismiss-notification-banner');

            // Check Permission and Show Banner
            if ("Notification" in window) {
                if (Notification.permission === "default") {
                    banner.style.display = 'block';
                    // Small delay for animation
                    setTimeout(() => {
                        banner.classList.remove('translate-y-full');
                    }, 100);
                }
            }

            // Enable Permissions
            if (enableBtn) {
                enableBtn.addEventListener('click', function() {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            new Notification("Notifications Enabled", {
                                body: "You will now receive updates instantly!",
                                icon: '/icon.png'
                            });
                            hideBanner();
                        }
                    });
                });
            }

            // Dismiss Banner
            if (dismissBtn) {
                dismissBtn.addEventListener('click', hideBanner);
            }

            function hideBanner() {
                banner.classList.add('translate-y-full');
                setTimeout(() => {
                    banner.style.display = 'none';
                }, 300);
            }

            let lastCount = {{ Auth::user()->unreadNotifications->count() }};
            
            setInterval(() => {
                fetch('{{ route("notifications.check") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update Badges
                        updateBadges(data.unread_count);
                        
                        if (data.unread_count > lastCount) {
                            // New Notification!
                            if (data.latest && "Notification" in window && Notification.permission === "granted") {
                                try {
                                    const notification = new Notification("LB Marketplace", {
                                        body: data.latest.body,
                                        icon: '/icon.png'
                                    });
                                    
                                    notification.onclick = function() {
                                        window.location.href = data.latest.url;
                                    };
                                } catch (e) {
                                    console.error("Push notification failed", e);
                                }
                            }
                        }
                        
                        lastCount = data.unread_count;
                    })
                    .catch(e => console.error('Notification poll error', e));
            }, 10000); // Check every 10 seconds

            function updateBadges(count) {
                const badges = document.querySelectorAll('.notification-badge');
                badges.forEach(badge => {
                    if (count > 0) {
                        badge.innerText = count;
                        badge.style.display = 'inline-flex';
                    } else {
                        badge.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endauth
</body>

</html>