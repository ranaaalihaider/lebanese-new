@extends('layouts.app')

@section('content')
    <!-- Sticky Header -->
    <header
        class="sticky top-0 md:top-16 bg-white/80 backdrop-blur-md z-30 border-b border-stone-100/50 shadow-sm transition-all supports-[backdrop-filter]:bg-white/60">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">
            <a href="{{ route('buyer.home') }}"
                class="p-2 -ml-2 text-stone-500 hover:text-emerald-700 bg-white/50 rounded-full hover:bg-emerald-50 transition-all active:scale-95 border border-transparent hover:border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7">
                    </path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-stone-900 tracking-tight">All Stores</h1>
        </div>
    </header>

    <div class="p-4 space-y-4 pb-24 md:pb-8 max-w-7xl mx-auto min-h-[60vh]">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
            @forelse($stores as $seller)
                <a href="{{ route('buyer.stores.show', $seller->id) }}" class="block group">
                    <div
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-stone-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50 flex items-center gap-4 transition-all active:scale-[0.98]">
                        <div
                            class="h-16 w-16 md:h-20 md:w-20 bg-stone-50 rounded-xl flex-shrink-0 overflow-hidden border border-stone-100 group-hover:scale-105 transition-transform relative">
                            @if($seller->sellerProfile->store_photo)
                                <img src="{{ asset('storage/' . $seller->sellerProfile->store_photo) }}" class="h-full w-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-600 font-bold text-xl">
                                    {{ substr($seller->sellerProfile->store_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2
                                class="text-base md:text-lg font-bold text-stone-900 truncate group-hover:text-emerald-700 transition-colors">
                                {{ $seller->sellerProfile->store_name }}</h2>
                            <p class="text-xs md:text-sm text-stone-500 truncate mb-2 font-medium">
                                {{ $seller->sellerProfile->storeType->name ?? 'General Store' }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100/50">Verified</span>
                            </div>
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-stone-50 flex items-center justify-center text-stone-400 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-16 text-center text-stone-400 bg-white rounded-2xl border-2 border-dashed border-stone-200">
                    <div class="bg-stone-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-stone-700 mb-1">No Stores Found</h3>
                    <p class="text-sm text-stone-400">Check back later for new sellers.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection