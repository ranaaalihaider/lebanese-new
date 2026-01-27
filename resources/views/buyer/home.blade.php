@extends('layouts.app')

@section('content')
    <div class="bg-stone-50 min-h-screen pb-12">
        <!-- Hero Section -->
        <div class="relative bg-emerald-600 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 mix-blend-multiply"></div>
            </div>
            <div class="relative max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">Welcome to Marketplace
                </h1>
                <p class="mt-4 text-xl text-emerald-100 max-w-3xl">Trust-first shopping platform for Lebanon. Quality
                    products from local artisans.</p>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Featured Products</h2>
                <a href="{{ route('buyer.stores') }}"
                    class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center">
                    See All <span aria-hidden="true" class="ml-1">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $index => $product)
                    <div
                        class="group relative bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col h-full overflow-hidden">
                        <!-- Badge (Randomized for visual variety like mockup) -->
                        <div class="absolute top-3 right-3 z-10">
                            @if($index % 4 == 0)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Popular</span>
                            @elseif($index % 4 == 1)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                            @elseif($index % 4 == 2)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">New</span>
                            @endif
                        </div>

                        <!-- Wishlist Button -->
                        <button type="button" onclick="window.toggleWishlist(event, {{ $product->id }}, this)"
                            class="absolute top-3 left-3 z-40 p-2 rounded-full bg-white/80 hover:bg-white transition-colors backdrop-blur-sm shadow-sm {{ auth()->check() && auth()->user()->hasWishlisted($product->id) ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500' }}">
                            <svg class="w-5 h-5 pointer-events-none {{ auth()->check() && auth()->user()->hasWishlisted($product->id) ? 'fill-current' : 'fill-none' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>

                        <!-- Product Image -->
                        <div
                            class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100 group-hover:opacity-95 transition-opacity h-64 flex items-center justify-center relative">
                            {{-- Placeholder logic if no image --}}
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover object-center">
                            @else
                                <div class="text-4xl">
                                    {{-- Emoji fallback based on name or random --}}
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

                                <div class="mt-2 text-xs text-stone-500">Final Price</div>
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-lg font-bold text-emerald-600">{{ number_format($product->final_price, 0) }}
                                        LBP</span>
                                </div>

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

            <!-- Install App Prompt (Visual Mockup) -->
            <div class="fixed bottom-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border border-gray-100 hidden md:block"
                x-data="{ show: true }" x-show="show">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 text-3xl">🛍️</div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-900">Install App</h4>
                        <p class="text-xs text-gray-500 mt-1">Add to home screen for a native app experience</p>
                        <div class="mt-3 flex gap-2">
                            <button
                                class="bg-emerald-600 text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-emerald-700">Install</button>
                            <button @click="show = false"
                                class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded hover:bg-gray-200">Later</button>
                        </div>
                    </div>
                </div>
            </div>
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