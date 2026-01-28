@extends('layouts.app')

@section('content')
    <div class="bg-stone-50 min-h-screen pb-12">
        <!-- Hero Section -->
        <div class="relative bg-emerald-600 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 mix-blend-multiply"></div>
            </div>
            <div class="relative max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    {{ __('Welcome to Marketplace') }}
                </h1>
                <p class="mt-4 text-xl text-emerald-100 max-w-3xl">
                    {{ __('Trust-first shopping platform for Lebanon. Quality products from local artisans.') }}</p>
            </div>
        </div>

        <!-- Featured Products -->
        <!-- Featured Products -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg md:text-2xl font-bold tracking-tight text-gray-900">@trans('Featured Products')</h2>
                <a href="{{ route('buyer.stores') }}"
                    class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center text-sm md:text-base">
                    @trans('See All') <span aria-hidden="true" class="ml-1">@trans('&rarr;')</span>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
                @foreach($featuredProducts as $index => $product)
                    <div class="bg-white rounded-lg border border-stone-200 overflow-hidden hover:shadow-md transition-shadow relative group">
                         <!-- Wishlist Button -->
                        <button type="button" onclick="window.toggleWishlist(event, {{ $product->id }}, this)"
                            class="absolute top-2 left-2 z-40 p-1.5 rounded-full bg-white/80 hover:bg-white transition-colors backdrop-blur-sm shadow-sm {{ auth()->check() && auth()->user()->hasWishlisted($product->id) ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500' }}">
                            <svg class="w-4 h-4 pointer-events-none {{ auth()->check() && auth()->user()->hasWishlisted($product->id) ? 'fill-current' : 'fill-none' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>
                        
                        <!-- Image -->
                        <div class="aspect-square bg-stone-100 relative group-hover:opacity-95 transition-opacity">
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/400x300?text=No+Image' }}"
                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                            
                            <!-- Wrapper Link -->
                             <a href="{{ route('buyer.products.show', $product) }}" class="absolute inset-0 z-10"></a>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-2.5">
                            <h3 class="font-semibold text-stone-900 text-sm leading-tight mb-0.5 line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</h3>
                            <p class="text-[10px] text-stone-500 mb-2 truncate">{{ $product->seller->sellerProfile->store_name ?? 'Store' }}</p>
                            
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-emerald-600">{{ number_format($product->final_price, 0) }} <span class="text-[9px] font-normal text-emerald-600/70">LBP</span></span>
                                @if($product->review_count > 0)
                                    <div class="flex items-center text-yellow-400 text-[10px]">
                                        <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        <span class="ml-0.5 text-stone-400">({{ $product->review_count }})</span>
                                    </div>
                                @endif
                            </div>

                             <!-- Add to Cart (Small) -->
                            <form action="{{ route('buyer.cart.store') }}" method="POST" class="mt-2 relative z-20">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-1.5 rounded-md transition-colors flex items-center justify-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    @trans('Add')
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Install App Prompt (Visual Mockup) - Hidden on desktop typically, showing for PWA feel -->
            <!-- ... kept existing ... -->
        </div>
    </div>

    @push('scripts')
        <script>
            window.toggleWishlist = function (event, productId, button) {

                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                @auth
                    // alert('Toggling wishlist...'); // Uncomment for debugging if needed
                    fetch('{{ route('buyer.wishlist.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                        .then(response => {
                            if (!response.ok) {
                                alert('Error: ' + response.statusText);
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Wishlist response:', data);
                            const svg = button.querySelector('svg');
                            if (data.status === 'added') {
                                button.classList.remove('text-gray-400');
                                button.classList.add('text-red-500');
                                svg.classList.remove('fill-none');
                                svg.classList.add('fill-current');
                            } else {
                                button.classList.add('text-gray-400');
                                button.classList.remove('text-red-500');
                                svg.classList.add('fill-none');
                                svg.classList.remove('fill-current');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Something went wrong. Please try again.');
                        });
                @else
                    window.location.href = "{{ route('login') }}";
                @endauth
                                        }
        </script>
    @endpush
@endsection