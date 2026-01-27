@extends('layouts.app')

@section('content')
    <div class="bg-stone-50 min-h-screen pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-stone-900">My Wishlist</h1>
                <a href="{{ route('buyer.home') }}"
                    class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center">
                    Continue Shopping <span aria-hidden="true" class="ml-1">&rarr;</span>
                </a>
            </div>

            @if($wishlistItems->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($wishlistItems as $product)
                        <div
                            class="group relative bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col h-full overflow-hidden">
                            <!-- Badge (Randomized for visual variety) -->
                            <div class="absolute top-3 right-3 z-10">
                                @if($product->id % 4 == 0)
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Popular</span>
                                @elseif($product->id % 4 == 1)
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                                @endif
                            </div>

                            <!-- Wishlist Button (Always Red/Active here) -->
                            <button type="button" onclick="window.toggleWishlist(event, {{ $product->id }}, this)"
                                class="absolute top-3 left-3 z-40 p-2 rounded-full bg-white/80 hover:bg-white transition-colors backdrop-blur-sm shadow-sm text-red-500 hover:text-red-600">
                                <svg class="w-5 h-5 pointer-events-none fill-current" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>

                            <!-- Product Image -->
                            <div
                                class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100 group-hover:opacity-95 transition-opacity h-64 flex items-center justify-center relative">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover object-center">
                                @else
                                    <div class="text-4xl">
                                        @if(str_contains(strtolower($product->name), 'bag')) 👜
                                        @elseif(str_contains(strtolower($product->name), 'perfume')) 🌸
                                        @elseif(str_contains(strtolower($product->name), 'coffee')) ☕
                                        @elseif(str_contains(strtolower($product->name), 'vase')) 🏺
                                        @elseif(str_contains(strtolower($product->name), 'oil')) 🫒
                                        @elseif(str_contains(strtolower($product->name), 'wood')) 🌲
                                        @elseif(str_contains(strtolower($product->name), 'scarf')) 🎀
                                        @else 📦
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                        <a href="{{ route('buyer.products.show', $product) }}">
                                            <span aria-hidden="true" class="absolute inset-0 z-0"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $product->seller->sellerProfile->store_name ?? $product->seller->name }}
                                    </p>

                                    <div class="mt-2 text-xs text-gray-400">Final Price</div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-lg font-bold text-emerald-600">{{ number_format($product->price, 0) }}
                                            LBP</span>
                                    </div>
                                    <p class="text-xs text-gray-400">+6% platform fee</p>

                                    <!-- Rating Stars -->
                                    <div class="mt-2 flex items-center">
                                        @if($product->review_count > 0)
                                            <div class="flex items-center text-yellow-400">
                                                @for($i = 0; $i < 5; $i++)
                                                    <svg class="h-4 w-4 flex-shrink-0 {{ $i < round($product->average_rating) ? 'fill-current' : 'text-gray-300 fill-current' }}"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-1 text-xs text-gray-400">({{ $product->review_count }})</span>
                                        @else
                                            <span class="text-xs text-gray-400">No reviews yet</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 flex gap-2 relative z-20">
                                    <a href="{{ route('buyer.products.show', $product) }}"
                                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2 rounded-lg text-center transition-colors">
                                        View Details
                                    </a>
                                    <form action="{{ route('buyer.cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-lg transition-colors"
                                            title="Add to Cart">
                                            <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $wishlistItems->links() }}
                </div>
            @else
                <div class="text-center py-24 bg-white rounded-2xl shadow-sm border border-stone-100">
                    <div
                        class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-6 text-stone-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-stone-900 mb-2">Your wishlist is empty</h2>
                    <p class="text-stone-500 mb-8 max-w-md mx-auto">Start exploring our marketplace and save your favorite items
                        for later.</p>
                    <a href="{{ route('buyer.home') }}"
                        class="inline-block bg-emerald-600 text-white font-bold py-3 px-8 rounded-full hover:bg-emerald-700 transition-colors shadow-lg">
                        Browse Products
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.toggleWishlist = function (event, productId, button) {

            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            @auth
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
                        if (data.status === 'removed') {
                            // Remove the element from the DOM with a nice fade out effect if possible, or just remove
                            const card = button.closest('.group');
                            if (card) {
                                card.remove(); // Simple remove
                            }

                            // If no items left, reload to show empty state
                            if (document.querySelectorAll('.group').length === 0) {
                                location.reload();
                            }
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