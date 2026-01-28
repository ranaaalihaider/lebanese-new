@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-emerald-900 py-24 sm:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <svg class="h-full w-full text-emerald-100" fill="currentColor" viewBox="0 0 100 100"
                preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z"></path>
            </svg>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">@trans('About Lebanese Marketplace')</h1>
            <p class="mt-6 text-xl text-emerald-100 max-w-2xl mx-auto">@trans('Connecting Lebanon\'s finest artisans, growers, and creators with the world. One unified platform for all things Lebanese.')</p>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-stone-900 mb-6">@trans('Our Mission')</h2>
                    <p class="text-lg text-stone-600 mb-6 leading-relaxed">
                        @trans('In the heart of Lebanon, creativity and resilience have always flourished. Our mission is to digitize the soulful marketplace of Lebanon, giving local sellers a powerful platform to reach buyers across the nation and beyond.')
                    </p>
                    <p class="text-lg text-stone-600 leading-relaxed">
                        @trans('We believe in fair trade, community growth, and the power of local production. By removing barriers to entry, we empower small business owners to thrive in the digital economy.')
                    </p>
                </div>
                <div class="relative h-96 rounded-2xl overflow-hidden shadow-2xl">
                    <!-- Placeholder for a nice cultural image -->
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center">
                        <span class="text-6xl">🇱🇧</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-stone-900">@trans('Why Choose Us?')</h2>
                <p class="mt-4 text-stone-500">@trans('Built for trust, speed, and community.')</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">@trans('Verified Sellers')</h3>
                    <p class="text-stone-500">@trans('Every seller is vetted to ensure authenticity and quality. Shop with confidence knowing you\'re supporting genuine local businesses.')</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">@trans('Fair Pricing')</h3>
                    <p class="text-stone-500">@trans('Direct from seller to buyer. We minimize standardized fees to ensure artisans get the profits they deserve while you get the best price.')</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">@trans('Community First')</h3>
                    <p class="text-stone-500">@trans('We are more than just a marketplace. We are a community of creators, dreamers, and supporters building a better economy together.')</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-emerald-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-emerald-200 text-sm uppercase tracking-wide">@trans('Active Sellers')</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">@trans('10k+')</div>
                    <div class="text-emerald-200 text-sm uppercase tracking-wide">@trans('Products')</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">@trans('5k+')</div>
                    <div class="text-emerald-200 text-sm uppercase tracking-wide">@trans('Happy Buyers')</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-emerald-200 text-sm uppercase tracking-wide">@trans('Support')</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="py-24 bg-white text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-stone-900 mb-6">@trans('Ready to join the movement?')</h2>
            <p class="text-stone-500 mb-10 text-lg">@trans('Whether you\'re looking to sell your creations or discover unique local gems, there\'s a place for you here.')</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register.buyer') }}"
                    class="inline-block bg-emerald-600 text-white font-bold py-3 px-8 rounded-full hover:bg-emerald-700 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    @trans('Start Shopping')
                </a>
                <a href="{{ route('register.seller') }}"
                    class="inline-block bg-white text-emerald-600 border-2 border-emerald-600 font-bold py-3 px-8 rounded-full hover:bg-emerald-50 transition-colors">
                    @trans('Become a Seller')
                </a>
            </div>
        </div>
    </div>
@endsection