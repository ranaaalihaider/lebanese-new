@extends('layouts.app')

@section('content')
    <div class="bg-white min-h-screen pb-24 md:pb-12">
        <!-- Top Nav (Back Button) -->
        <div
            class="fixed top-0 w-full z-20 p-4 md:p-6 flex justify-between items-center pointer-events-none max-w-7xl mx-auto left-0 right-0">
            <a href="{{ url()->previous() }}"
                class="bg-white/80 backdrop-blur-md text-stone-700 rounded-full p-2.5 shadow-sm pointer-events-auto hover:bg-emerald-50 hover:text-emerald-700 transition-colors border border-white/50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
        </div>

        <div class="md:grid md:grid-cols-2 md:gap-12 md:p-8 md:items-start max-w-7xl mx-auto">

            <!-- Image Gallery Column -->
            <div
                class="relative w-full aspect-square md:aspect-auto md:h-[600px] bg-stone-100 overflow-hidden md:rounded-3xl group">
                @if($product->photos->count() > 0)
                    <div
                        class="flex overflow-x-auto snap-x snap-mandatory h-full scrollbar-hide md:grid md:grid-cols-2 md:gap-2 md:overflow-visible relative">
                        @foreach($product->photos as $index => $photo)
                            <div
                                class="snap-center flex-shrink-0 w-full h-full relative md:w-auto md:h-64 md:rounded-xl overflow-hidden {{ $index === 0 ? 'md:col-span-2 md:h-96' : '' }}">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                    class="absolute inset-0 w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>

                    <!-- Mobile Pagination Badge -->
                    <div
                        class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full md:hidden pointer-events-none">
                        1 / {{ $product->photos->count() }}
                    </div>
                @else
                    <img src="https://placehold.co/600x600?text=No+Image" class="w-full h-full object-cover">
                @endif
            </div>

            <!-- Content Column (Sticky on Desktop) -->
            <div
                class="px-5 py-8 md:px-0 md:py-0 -mt-6 md:mt-0 relative bg-white rounded-t-[2rem] md:rounded-none shadow-[0_-10px_40px_rgba(0,0,0,0.05)] md:shadow-none z-10 md:sticky md:top-24">

                <!-- Decorative Pull Bar for Mobile -->
                <div class="w-12 h-1.5 bg-stone-200 rounded-full mx-auto mb-6 md:hidden"></div>

                <!-- Header -->
                <div class="flex flex-col gap-3 mb-8">
                    <div class="flex justify-between items-start gap-4">
                        <h1 class="text-2xl md:text-4xl font-bold text-stone-900 leading-tight tracking-tight">
                            {{ $product->name }}
                        </h1>
                        <span
                            class="text-xl md:text-3xl font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100">${{ number_format($product->final_price, 2) }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($product->type)
                            <span
                                class="text-sm font-medium text-stone-500 bg-stone-100 px-3 py-1 rounded-full">{{ $product->type->name }}</span>
                        @endif

                        <!-- Average Rating -->
                        @if($product->reviews->count() > 0)
                            <div class="flex items-center gap-1">
                                <div class="flex items-center text-yellow-400">
                                    <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-sm font-bold text-stone-900">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="text-sm text-stone-500">({{ $product->reviews->count() }} reviews)</span>
                            </div>
                        @else
                            <span class="text-sm text-stone-400">No reviews yet</span>
                        @endif
                    </div>
                </div>

                <!-- Seller Info -->
                <div
                    class="flex items-center gap-4 p-4 border border-stone-100 bg-stone-50/50 rounded-2xl mb-8 group cursor-pointer hover:bg-emerald-50/30 transition-colors">
                    <div
                        class="w-12 h-12 rounded-full bg-white border border-stone-200 flex items-center justify-center text-stone-400 group-hover:border-emerald-200 group-hover:text-emerald-500 transition-colors shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mb-0.5">Sold by</p>
                        <p class="text-stone-900 font-bold text-base">{{ $product->seller->sellerProfile->store_name }}</p>
                    </div>
                    <a href="{{ route('buyer.stores.show', $product->seller->id) }}"
                        class="text-emerald-700 font-bold text-xs bg-white px-4 py-2 rounded-full border border-emerald-100 shadow-sm hover:shadow hover:scale-105 transition-all">
                        Visit Store
                    </a>
                </div>

                <!-- Description -->
                <div class="mb-10">
                    <h3 class="font-bold text-stone-900 mb-3 text-lg">About this product</h3>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-emerald-600">{{ number_format($product->final_price, 0) }} LBP</p>
                        <p class="text-xs text-stone-500 mt-1">Final price including calculated fees</p>
                    </div>
                    <div class="text-stone-600 leading-relaxed text-base space-y-4">
                        <div class="prose prose-stone prose-sm max-w-none">
                            {{ $product->description ?? 'No description available for this product.' }}
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="mb-10 border-t border-stone-100 pt-8">
                    <h3 class="font-bold text-stone-900 mb-6 text-lg">Customer Reviews</h3>

                    @if($product->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($product->reviews as $review)
                                <div class="bg-stone-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-xs">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-sm text-stone-900">{{ $review->user->name }}</span>
                                        </div>
                                        <span class="text-xs text-stone-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div class="flex items-center text-yellow-400 mb-2">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="h-4 w-4 {{ $i < $review->rating ? 'fill-current' : 'text-stone-300 fill-current' }}"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>

                                    @if($review->comment)
                                        <p class="text-sm text-stone-600 mb-3">{{ $review->comment }}</p>
                                    @endif

                                    @if($review->image_path)
                                        <div class="mt-2 w-24 h-24 rounded-lg overflow-hidden cursor-pointer hover:opacity-90 transition-opacity"
                                            onclick="window.open('{{ asset('storage/' . $review->image_path) }}', '_blank')">
                                            <img src="{{ asset('storage/' . $review->image_path) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-stone-50 rounded-xl border border-dashed border-stone-200">
                            <p class="text-stone-500 text-sm">No reviews yet. Be the first to review this product!</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if(!auth()->check() || (auth()->check() && auth()->user()->role === 'buyer'))
                    {{-- Only show buy button to guests and buyers, not to admins or sellers --}}
                    <div class="space-y-3">
                        @auth
                            @if(auth()->id() !== $product->seller_id)
                                <a href="{{ route('buyer.checkout.show', $product) }}"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl hover:shadow-emerald-900/20 flex items-center justify-center gap-3 transform hover:-translate-y-0.5 transition-all text-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Proceed to Checkout
                                </a>
                            @else
                                <div
                                    class="w-full bg-stone-100 text-stone-500 font-bold py-4 px-6 rounded-2xl flex items-center justify-center gap-3 cursor-not-allowed">
                                    You own this product
                                </div>
                            @endif
                        @else
                            <a href="{{ route('buyer.checkout.show', $product) }}"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl hover:shadow-emerald-900/20 flex items-center justify-center gap-3 transform hover:-translate-y-0.5 transition-all text-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Log in to Buy
                            </a>
                        @endauth

                        <p class="text-center text-xs text-stone-400 mt-3 font-medium">Secure checkout via Lebanese Marketplace.
                        </p>
                    </div>
                @else
                    {{-- Admin/Seller view - show informational message --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-blue-900 mb-1">Admin/Seller View</p>
                                <p class="text-xs text-blue-700">You are viewing this product as {{ auth()->user()->role }}.
                                    Buyer checkout features are hidden.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Sticky Action Bar (Hidden on Desktop, Only for Buyers) -->
        @if(!auth()->check() || (auth()->check() && auth()->user()->role === 'buyer'))
            <div
                class="fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-xl border-t border-stone-200/50 p-4 pb-safe shadow-[0_-5px_30px_rgba(0,0,0,0.1)] z-50 md:hidden">
                <div class="max-w-md mx-auto flex gap-3">
                    @auth
                        @if(auth()->id() !== $product->seller_id)
                            <a href="{{ route('buyer.checkout.show', $product) }}"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-900/20 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                                Buy Now - ${{ number_format($product->final_price, 2) }}
                            </a>
                        @else
                            <div
                                class="flex-1 bg-stone-100 text-stone-500 font-bold py-3.5 px-4 rounded-xl flex items-center justify-center">
                                You own this
                            </div>
                        @endif
                    @else
                        <a href="{{ route('buyer.checkout.show', $product) }}"
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-900/20 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                            Log in to Buy
                        </a>
                    @endauth
                </div>
            </div>
        @endif
    </div>
@endsection