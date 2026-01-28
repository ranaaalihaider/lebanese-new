<!-- Mobile Top Header for Guests -->
@guest
    <div class="fixed top-0 left-0 w-full bg-gradient-to-r from-emerald-600 to-emerald-700 z-50 md:hidden shadow-lg">
        <div class="flex items-center justify-between px-4 py-3">
            <!-- Logo/Company Name -->
            <a href="{{ route('buyer.home') }}" class="flex items-center gap-2">
                @php
                    $logoPath = \App\Models\Setting::getSetting('site_logo');
                    $siteName = \App\Models\Setting::getSetting('site_name') ?? 'Lebanese Marketplace';
                @endphp
                @if($logoPath)
                    <div class="bg-white p-2 rounded-lg shadow-md">
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="w-6 h-6 object-contain">
                    </div>
                @else
                    <div class="bg-white p-2 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                @endif
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">{{ $siteName }}</h1>
                    <p class="text-emerald-100 text-xs">@trans('Guest')</p>
                </div>
            </a>

            <!-- Guest Profile / Menu -->
            <div class="relative ml-auto flex items-center gap-2" x-data="{ open: false }">
                <button id="pwa-install-btn-mobile"
                    class="hidden items-center justify-center w-8 h-8 bg-white/20 rounded-full text-white hover:bg-white/30 transition-colors"
                    style="display: none;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </button>

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
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-stone-200 overflow-hidden"
                    style="display: none;">

                    <div class="px-4 py-3 border-b border-stone-100 bg-stone-50">
                        <p class="text-sm font-bold text-stone-900">@trans('Welcome, Guest')</p>
                        <p class="text-xs text-stone-500">@trans('Sign in to manage orders')</p>
                    </div>

                    <!-- Language Selection -->
                    <div class="px-4 py-2 border-b border-stone-100">
                        <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2">@trans('Language')</p>
                        <div class="space-y-1">
                            @foreach(\App\Helpers\LanguageHelper::getLanguages() as $code => $detail)
                                <a href="{{ route('lang.switch', $code) }}"
                                    class="flex items-center justify-between text-sm text-stone-700 hover:text-emerald-600 hover:bg-emerald-50 rounded px-2 py-1 transition-colors">
                                    <span class="flex items-center gap-2">
                                        <span class="text-lg">{{ $detail['flag'] }}</span>
                                        {{ $detail['name'] }}
                                    </span>
                                    @if(app()->getLocale() == $code)
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        </div>
    </div>

    <!-- Spacer for fixed header (mobile only) -->
    <div class="h-14 md:hidden"></div>
@endguest

@auth
    <!-- Mobile Top Header (Always Visible) -->
    <div class="fixed top-0 left-0 w-full bg-gradient-to-r from-emerald-600 to-emerald-700 z-50 md:hidden shadow-lg">
        <div class="flex items-center justify-between px-4 py-3">
            <!-- Logo/Company Name -->
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.dashboard' : (Auth::user()->role === 'seller' ? 'seller.dashboard' : 'buyer.home')) }}"
                class="flex items-center gap-2">
                @php
                    $logoPath = \App\Models\Setting::getSetting('site_logo');
                @endphp
                @if($logoPath)
                    <div class="bg-white p-2 rounded-lg shadow-md">
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="w-6 h-6 object-contain">
                    </div>
                @else
                    <div class="bg-white p-2 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                @endif
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">@trans('LB Marketplace')</h1>
                    <p class="text-emerald-100 text-xs capitalize">{{ Auth::user()->role }}</p>
                </div>
            </a>

            <!-- Language Switcher Removed (Moved to User Dropdown) -->

            <!-- Actions Section (Cart & Profile) -->
            <div class="flex items-center gap-3">
                <!-- PWA Install Button (Mobile Auth) -->
                <button id="pwa-install-btn-mobile"
                    class="hidden items-center justify-center w-8 h-8 bg-white/20 rounded-full text-white hover:bg-white/30 transition-colors"
                    style="display: none;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </button>
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative text-emerald-100 hover:text-white transition-colors pt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span
                            class="notification-badge absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 transform bg-red-600 rounded-full"
                            style="{{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'display: none;' }}">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="fixed left-4 right-4 top-16 bg-white rounded-xl shadow-xl border border-stone-200 overflow-hidden z-50 py-1"
                        style="display: none;">
                        <div class="px-4 py-2 border-b border-stone-100 bg-stone-50 flex justify-between items-center">
                            <span class="font-bold text-stone-700 text-sm">@trans('Notifications')</span>
                            <span class="text-xs text-stone-500">{{ Auth::user()->unreadNotifications->count() }}
                                @trans('Unread')</span>
                        </div>

                        <div class="max-h-60 overflow-y-auto">
                            @forelse(Auth::user()->notifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}"
                                    class="block px-4 py-3 hover:bg-stone-50 transition-colors border-b border-stone-100 {{ $notification->read_at ? 'opacity-60' : '' }}">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <div
                                                class="w-2 h-2 rounded-full {{ $notification->read_at ? 'bg-stone-300' : 'bg-emerald-500' }}">
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-stone-800 leading-snug">
                                                {{ $notification->data['message'] ?? 'New Notification' }}
                                            </p>
                                            <p class="text-xs text-stone-500 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
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
                @if(Auth::user()->role === 'buyer')
                    <a href="{{ route('buyer.cart.index') }}"
                        class="relative text-emerald-100 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        @if(Auth::user()->carts()->count() > 0)
                            <span
                                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 transform bg-red-600 rounded-full">
                                {{ Auth::user()->carts()->count() }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Profile Section -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 bg-emerald-800/50 hover:bg-emerald-800 rounded-full pl-3 pr-2 py-1.5 transition-colors">
                        <span
                            class="text-white text-sm font-semibold max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                        <div
                            class="h-8 w-8 rounded-full bg-white flex items-center justify-center text-emerald-600 font-bold text-sm shadow-md">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-stone-200 overflow-hidden"
                        style="display: none;">

                        <div class="px-4 py-3 border-b border-stone-100 bg-stone-50">
                            <p class="text-sm font-bold text-stone-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-stone-500">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Language Selection -->
                        <div class="px-4 py-2 border-b border-stone-100">
                            <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2">@trans('Language')
                            </p>
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

                        <div class="py-1">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.settings') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-stone-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    @trans('Settings')
                                </a>
                            @else
                                <a href="{{ Auth::user()->role === 'buyer' ? route('buyer.orders.index') : route('dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-stone-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ Auth::user()->role === 'buyer' ? 'My Orders' : 'Profile' }}
                                </a>
                            @endif

                            @if(Auth::user()->role === 'buyer')
                                <a href="{{ route('buyer.wishlist.index') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-stone-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    @trans('Wishlist')
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-stone-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                @trans('Profile')
                            </a>

                            <div class="border-t border-stone-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    @trans('Logout')
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacer for fixed header (mobile only) -->
    <div class="h-14 md:hidden"></div>
@endauth