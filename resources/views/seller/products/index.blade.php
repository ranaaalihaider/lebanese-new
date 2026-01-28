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
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>@trans('Inactive')</option>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($products as $product)
                <div
                    class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden flex flex-row sm:flex-col h-32 sm:h-auto">
                    {{-- Image --}}
                    <div class="w-32 sm:w-full h-full sm:h-48 flex-shrink-0 bg-stone-100">
                        @php
                            $imagePath = $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/400x300?text=No+Image';
                        @endphp
                        <img src="{{ $imagePath }}" class="w-full h-full object-cover"
                            onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=Image+Error';">
                    </div>

                    {{-- Content --}}
                    <div class="p-3 md:p-4 flex flex-col justify-between w-full">
                        <div>
                            <div class="flex justify-between items-start">
                                <h2 class="text-sm md:text-xl font-bold text-stone-900 line-clamp-2 leading-tight">
                                    {{ $product->name }}
                                </h2>
                                <span
                                    class="text-xs bg-stone-100 text-stone-500 px-1.5 py-0.5 rounded ml-2">{{ $product->type->name }}</span>
                            </div>

                            {{-- Price Calculation Visualization --}}
                            <div class="mt-2 text-xs md:text-sm space-y-1">
                                <div class="flex justify-between text-stone-500">
                                    <span>@trans('Base:')</span>
                                    <span>{{ number_format($product->price, 0) }} LBP</span>
                                </div>
                                <div class="flex justify-between text-stone-400">
                                    <span>@trans('Fee:')</span>
                                    <span>{{ number_format($product->final_price - $product->price, 0) }} LBP</span>
                                </div>
                                <div class="flex justify-between font-bold text-emerald-600 pt-1 border-t border-stone-100">
                                    <span>@trans('Final:')</span>
                                    <span>{{ number_format($product->final_price, 0) }} LBP</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end gap-3 mt-2 items-center">
                            <form action="{{ route('seller.products.toggle', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-xs font-bold px-2 py-1 rounded border {{ $product->is_active ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : 'text-stone-400 border-stone-200 bg-stone-50' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                            <a href="{{ route('seller.products.edit', $product) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs md:text-sm font-bold">@trans('Edit')</a>
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 text-xs md:text-sm font-bold">@trans('Delete')</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection