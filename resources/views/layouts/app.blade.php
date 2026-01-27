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

            <!-- Desktop Links - Wraps to second line on smaller screens -->
            <div class="flex items-center flex-wrap gap-x-3 md:gap-x-4 lg:gap-x-6 xl:gap-x-8 gap-y-2 flex-1">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Dashboard</a>
                        <a href="{{ route('admin.orders.index') }}"
                            class="{{ request()->routeIs('admin.orders*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Orders</a>
                        <a href="{{ route('admin.sellers') }}"
                            class="{{ request()->routeIs('admin.sellers*') || request()->routeIs('admin.stores*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Sellers</a>
                        <a href="{{ route('admin.buyers') }}"
                            class="{{ request()->routeIs('admin.buyers*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Buyers</a>
                        <a href="{{ route('admin.products.index') }}"
                            class="{{ request()->routeIs('admin.products*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Products</a>
                        <a href="{{ route('admin.earnings') }}"
                            class="{{ request()->routeIs('admin.earnings') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Revenue</a>
                        <a href="{{ route('admin.payouts.index') }}"
                            class="{{ request()->routeIs('admin.payouts*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Payouts</a>

                        <!-- Admin Dropdown for Management -->
                        <div class="relative group flex-shrink-0" x-data="{ open: false }">
                            <button @click="open = !open" @mouseenter="open = true"
                                class="flex items-center gap-1 text-emerald-100/80 hover:text-white transition-colors focus:outline-none text-sm md:text-base">
                                <span>Lists</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" @mouseleave="open = false"
                                class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">
                                <a href="{{ route('admin.product-types.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Product Categories</a>
                                <a href="{{ route('admin.store-types.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Store Categories</a>
                            </div>
                        </div>

                    @elseif(Auth::user()->role == 'seller')
                        <a href="{{ route('seller.dashboard') }}"
                            class="{{ request()->routeIs('seller.dashboard') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Dashboard</a>
                        <a href="{{ route('seller.products.index') }}"
                            class="{{ request()->routeIs('seller.products*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Products</a>

                        <!-- Orders Dropdown -->
                        <div class="relative group flex-shrink-0" x-data="{ open: false }">
                            <button @click="open = !open" @mouseenter="open = true"
                                class="flex items-center gap-1 {{ request()->routeIs('seller.orders*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors focus:outline-none text-sm md:text-base">
                                <span>Orders</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" @mouseleave="open = false"
                                class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">
                                <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pending Orders</a>
                                <a href="{{ route('seller.orders.index', ['status' => 'new']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New Orders</a>
                                <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Completed Orders</a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('seller.orders.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold">All Orders</a>
                            </div>
                        </div>

                        <a href="{{ route('seller.earnings') }}"
                            class="{{ request()->routeIs('seller.earnings') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Earnings</a>


                    @else
                        <!-- Buyer / Other Role -->
                        <a href="{{ route('buyer.home') }}"
                            class="{{ request()->routeIs('buyer.home') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">Home</a>
                        <a href="{{ route('buyer.orders.index') }}"
                            class="{{ request()->routeIs('buyer.orders*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors text-sm md:text-base flex-shrink-0">My
                            Orders</a>
                        <a href="{{ route('buyer.wishlist.index') }}"
                            class="{{ request()->routeIs('buyer.wishlist*') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">Wishlist</a>
                        <a href="{{ route('buyer.stores') }}"
                            class="{{ request()->routeIs('buyer.stores') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">Stores</a>
                        <a href="{{ route('about') }}" class="text-emerald-100/80 hover:text-white transition-colors">About
                            Us</a>
                    @endif
                @else
                    <!-- Guest -->
                    <a href="{{ route('buyer.home') }}"
                        class="{{ request()->routeIs('buyer.home') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">Home</a>
                    <a href="{{ route('buyer.stores') }}"
                        class="{{ request()->routeIs('buyer.stores') ? 'text-emerald-100 font-semibold' : 'text-emerald-100/80 hover:text-white' }} transition-colors">Stores</a>
                    <a href="{{ route('about') }}" class="text-emerald-100/80 hover:text-white transition-colors">About
                        Us</a>
                @endauth
            </div>

            <!-- Right Side (Profile) -->
            <div class="flex items-center gap-4">

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
                                <span class="sr-only">Open user menu</span>
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
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.settings') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                            @else
                                <a href="{{ Auth::user()->role === 'buyer' ? route('buyer.orders.index') : route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    {{ Auth::user()->role === 'buyer' ? 'My Orders' : 'Visit Account' }}
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold bg-white text-emerald-800 px-4 py-1.5 rounded-full hover:bg-emerald-50 transition-colors">
                        Login
                    </a>
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
                <strong class="font-bold">OTP:</strong>
                <span class="block sm:inline">{{ session('otp_message') }}</span>
            </div>
        @endif

        <main class="relative z-0">
            @yield('content')
        </main>
    </div>

    <!-- Mobile PWA Bottom Navigation (Auto-included for authenticated users) -->
    @include('components.bottom-nav')

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered:', registration);
                    })
                    .catch(error => {
                        console.log('SW registration failed:', error);
                    });
            });
        }
    </script>
    @stack('scripts')
</body>

</html>