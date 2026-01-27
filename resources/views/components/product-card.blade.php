@props(['product'])

<a href="{{ route('buyer.products.show', $product) }}" class="block group">
    <div
        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
        <div class="relative aspect-square">
            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/400x400?text=No+Image' }}"
                class="absolute inset-0 w-full h-full object-cover">
        </div>
        <div class="p-3">
            <h3 class="font-bold text-gray-900 truncate text-sm">{{ $product->name }}</h3>
            <div class="flex items-center justify-between mt-1">
                <p
                    class="text-green-600 font-bold text-sm bg-green-50 px-2 py-0.5 rounded-full border border-green-100">
                    ${{ number_format($product->final_price, 2) }}
                </p>
            </div>
            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1 truncate">
                <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                </svg>
                {{ $product->seller->sellerProfile->store_name }}
            </p>
        </div>
    </div>
</a>