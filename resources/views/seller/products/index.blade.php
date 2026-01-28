@extends('layouts.app')

@section('content')
    <div class="p-4 md:p-6 pb-24 md:pb-6">
        <!-- Stats Row -->
        <div class="grid grid-cols-3 gap-3 md:gap-4 mb-6">
            <div class="bg-white rounded-xl border border-stone-200 p-4 text-center">
                <p class="text-xs text-stone-500 uppercase font-bold">@trans('Total')</p>
                <p class="text-xl md:text-2xl font-bold text-stone-900 mt-1">{{ $totalProducts }}</p>
            </div>
            <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4 text-center">
                <p class="text-xs text-emerald-600 uppercase font-bold">@trans('Active')</p>
                <p class="text-xl md:text-2xl font-bold text-emerald-700 mt-1">{{ $activeProducts }}</p>
            </div>
            <div class="bg-stone-50 rounded-xl border border-stone-200 p-4 text-center">
                <p class="text-xs text-stone-500 uppercase font-bold">@trans('Inactive')</p>
                <p class="text-xl md:text-2xl font-bold text-stone-600 mt-1">{{ $inactiveProducts }}</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-stone-900">@trans('My Products')</h1>

            <div class="flex items-center gap-4 w-full md:w-auto">
                {{-- Filter Form --}}
                <form action="{{ route('seller.products.index') }}" method="GET"
                    class="flex items-center gap-2 flex-1 md:flex-initial">
                    <!-- Category Filter -->
                    <select name="category_id" onchange="this.form.submit()"
                        class="pl-4 pr-10 py-2 rounded-full border-stone-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 shadow-sm w-32 md:w-40">
                        <option value="all">@trans('All Categories')</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ request('category_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="pl-4 pr-10 py-2 rounded-full border-stone-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 shadow-sm w-32 md:w-40">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>@trans('All Status')</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>@trans('Active')</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>@trans('Inactive')
                        </option>
                    </select>
                </form>

                <div class="flex items-center gap-4">

                    <a href="{{ route('seller.products.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-full shadow hover:bg-blue-700 text-sm font-bold flex items-center gap-1 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="hidden md:inline">@trans('Add Product')</span>
                        <span class="md:hidden">@trans('Add')</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
            @foreach($products as $product)
                <div
                    class="bg-white rounded-lg border border-stone-200 overflow-hidden hover:shadow-md transition-shadow relative group flex flex-col">

                    {{-- Image & Status Badge --}}
                    <div class="aspect-square bg-stone-100 relative group-hover:opacity-95 transition-opacity">
                        @php
                            $imagePath = $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/400x300?text=No+Image';
                        @endphp
                        <img src="{{ $imagePath }}" class="w-full h-full object-cover"
                            onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=Image+Error';">

                        {{-- Status Badge --}}
                        <div class="absolute top-2 left-2">
                            <form action="{{ route('seller.products.toggle', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="shadow-sm rounded-full bg-white px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-2.5 flex flex-col flex-grow">
                        {{-- Title & Category --}}
                        <div class="mb-2">
                            <h3 class="font-semibold text-stone-900 text-sm leading-tight mb-0.5 line-clamp-1"
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                            <p class="text-[10px] text-stone-500 truncate">{{ $product->type->name }}</p>
                        </div>

                        {{-- Price --}}
                        <div class="flex items-baseline gap-1.5 mb-3">
                            <span class="text-sm font-bold text-emerald-600 block">{{ number_format($product->final_price, 0) }}
                                <span class="text-[9px] font-normal text-emerald-600/70">LBP</span></span>
                            @if($product->final_price != $product->price)
                                <span
                                    class="text-[10px] text-stone-400 line-through decoration-stone-300">{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>

                        {{-- Actions (Push to bottom) --}}
                        <div class="mt-auto grid grid-cols-4 gap-2">
                            <a href="{{ route('seller.products.edit', $product) }}"
                                class="col-span-3 block w-full bg-stone-50 hover:bg-stone-100 border border-stone-100 text-stone-700 hover:text-stone-900 text-center py-1.5 rounded-md font-semibold transition-colors text-xs">
                                @trans('Edit')
                            </a>
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="col-span-1"
                                onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full h-full flex items-center justify-center bg-red-50 hover:bg-red-100 border border-red-50 text-red-500 hover:text-red-700 rounded-md transition-colors"
                                    title="@trans('Delete')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection