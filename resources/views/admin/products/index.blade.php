@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-stone-50 to-stone-100 pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-stone-200 px-4 md:px-6 py-6 md:py-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-stone-900">All Products</h1>
                        <p class="text-stone-600 text-sm md:text-base mt-1">Browse and manage all marketplace products</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 md:px-6 md:py-8">
            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
                <div class="bg-white rounded-xl border border-stone-200 p-4">
                    <p class="text-xs text-stone-500 uppercase font-bold">Total Products</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4">
                    <p class="text-xs text-emerald-600 uppercase font-bold">Active</p>
                    <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $activeProducts }}</p>
                </div>
                <div class="hidden md:block bg-purple-50 rounded-xl border border-purple-200 p-4">
                    <p class="text-xs text-purple-600 uppercase font-bold">Categories</p>
                    <p class="text-2xl font-bold text-purple-700 mt-1">{{ \App\Models\ProductType::count() }}</p>
                </div>
            </div>

            <!-- Search Filters -->
            <div class="bg-white rounded-xl border border-stone-200 p-4 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-stone-900">Filters</h2>
                    <button onclick="toggleProductFilters()"
                        class="px-4 py-2 bg-stone-100 text-stone-600 rounded-xl flex items-center gap-2 hover:bg-stone-200 transition-colors text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Toggle Filters
                    </button>
                </div>

                <div id="product-filters-container"
                    class="{{ request()->anyFilled(['product_name', 'store_id', 'category_id', 'status']) ? '' : 'hidden' }}">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <!-- Product Name Search -->
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-stone-600 mb-2">Product Name</label>
                            <input type="text" name="product_name" value="{{ request('product_name') }}"
                                placeholder="Search by product name..."
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                        </div>

                        <!-- Store Filter (Searchable) -->
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-stone-600 mb-2">Store</label>
                            <select name="store_id" id="product-store-select"
                                class="searchable-select w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                                <option value="all">All Stores</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ request('store_id') == $seller->id ? 'selected' : '' }}>
                                        {{ $seller->sellerProfile->store_name ?? $seller->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div class="w-full md:w-40">
                            <label class="block text-xs font-bold text-stone-600 mb-2">Category</label>
                            <select name="category_id"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm h-[38px]">
                                <option value="all">All</option>
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('category_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full md:w-40">
                            <label class="block text-xs font-bold text-stone-600 mb-2">Status</label>
                            <select name="status"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm h-[38px]">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full md:w-auto px-6 py-2 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mobile: Product Cards -->
            <div class="md:hidden space-y-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                        <div class="flex gap-4 p-4">
                            <!-- Image -->
                            <div class="w-20 h-20 bg-stone-100 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer"
                                onclick="window.location.href='{{ route('buyer.products.show', $product) }}'">
                                <img src="{{ $product->photos->first() ? asset('storage/' . $product->photos->first()->photo_path) : 'https://placehold.co/100' }}"
                                    class="w-full h-full object-cover" alt="{{ $product->name }}">
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-stone-900 truncate cursor-pointer active:text-emerald-600"
                                    onclick="window.location.href='{{ route('buyer.products.show', $product) }}'">
                                    {{ $product->name }}</h3>
                                <p class=" text-xs text-stone-500
                                            text-stone-500 mt-1">
                                    {{ $product->type->name }}
                                </p>
                                <div class="mt-2 text-xs">
                                    <div class="flex justify-between text-stone-500"><span>Base:</span>
                                        <span>${{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-stone-400"><span>Fee:</span>
                                        <span>${{ number_format($product->final_price - $product->price, 2) }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between font-bold text-emerald-600 border-t border-dashed border-stone-200 mt-1 pt-1">
                                        <span>Final:</span> <span>${{ number_format($product->final_price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Info & Actions -->
                        <div class="px-4 py-3 bg-stone-50 border-t border-stone-100 flex items-center justify-between">
                            <div class="flex-1 min-w-0 mr-2">
                                <p class="text-xs text-stone-500">Seller: {{ $product->seller->sellerProfile->store_name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.products.toggle', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xs font-bold px-2 py-1 rounded border {{ $product->is_active ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : 'text-stone-400 border-stone-200 bg-stone-50' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.stores.show', $product->seller_id) }}"
                                    class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    View Store
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop: Product Grid -->
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div
                        class="bg-white rounded-2xl border border-stone-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                        <!-- Image -->
                        <div class="aspect-square bg-stone-100 overflow-hidden relative cursor-pointer"
                            onclick="window.location.href='{{ route('buyer.products.show', $product) }}'">
                            <img src="{{ $product->photos->first() ? asset('storage/' . $product->photos->first()->photo_path) : 'https://placehold.co/400' }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                alt="{{ $product->name }}">
                            <span
                                class="absolute top-3 right-3 text-white text-xs font-bold px-3 py-1 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-stone-500' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-bold text-stone-900 line-clamp-2 flex-1 cursor-pointer hover:text-emerald-600 transition-colors"
                                    onclick="window.location.href='{{ route('buyer.products.show', $product) }}'">
                                    {{ $product->name }}
                                </h3>
                            </div>
                            <p class="text-sm text-stone-500 mb-3">{{ $product->type->name }}</p>
                            <div class="mb-4 text-sm">
                                <div class="flex justify-between text-stone-500"><span>Base:</span>
                                    <span>${{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-stone-400"><span>Fee:</span>
                                    <span>${{ number_format($product->final_price - $product->price, 2) }}</span>
                                </div>
                                <div
                                    class="flex justify-between font-bold text-emerald-600 border-t border-dashed border-stone-200 mt-1 pt-1 text-base">
                                    <span>Final:</span> <span>${{ number_format($product->final_price, 2) }}</span>
                                </div>
                            </div>

                            <!-- Seller -->
                            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                                <form action="{{ route('admin.products.toggle', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xs font-bold px-2 py-1 rounded border {{ $product->is_active ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : 'text-stone-400 border-stone-200 bg-stone-50' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                                <div class="flex items-center gap-2">
                                    <div class="min-w-0 text-right">
                                        <p class="text-xs text-stone-500">Seller</p>
                                        <p class="text-xs font-bold text-stone-700 truncate max-w-[100px]">
                                            {{ $product->seller->sellerProfile->store_name }}
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.stores.show', $product->seller_id) }}"
                                        class="bg-blue-50 text-blue-700 hover:bg-blue-100 p-2 rounded-lg transition-colors flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif

            @if($products->count() === 0)
                <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-stone-900">No products found</h3>
                    <p class="text-stone-500 mt-1">Products will appear here as sellers add them</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #e7e5e4;
            border-radius: 0.75rem;
            height: 38px;
            padding: 4px 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-dropdown {
            border: 1px solid #e7e5e4;
            border-radius: 0.75rem;
        }
    </style>

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.searchable-select').select2({
                placeholder: "Select a store",
                allowClear: true,
                width: '100%'
            });
        });

        function toggleProductFilters() {
            document.getElementById('product-filters-container').classList.toggle('hidden');
        }
    </script>

    <!-- Mobile Bottom Navigation -->
    @include('components.bottom-nav')
@endsection