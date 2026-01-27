@extends('layouts.app')

@section('content')
    @include('components.top-header')

    <div class="min-h-screen bg-stone-50 pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-stone-200 px-4 md:px-6 py-6 md:py-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl md:text-3xl font-bold text-stone-900">Manage Lists</h1>
                <p class="text-stone-600 text-sm md:text-base mt-1">Product Categories & Store Types</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 md:px-6 md:py-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Product Categories -->
                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Product Categories</h2>
                        <p class="text-purple-100 text-sm">{{ $productTypes->count() }} categories</p>
                    </div>

                    <div class="p-6">
                        <a href="{{ route('admin.product-types.index') }}"
                            class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-xl mb-4 text-center transition-colors">
                            Manage Product Categories
                        </a>

                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            @forelse($productTypes as $type)
                                <div
                                    class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                                            {{ substr($type->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-stone-900">{{ $type->name }}</p>
                                            <p class="text-xs text-stone-500">{{ $type->products_count ?? 0 }} products</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-stone-500 py-8">No product categories yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Store Types -->
                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Store Types</h2>
                        <p class="text-blue-100 text-sm">{{ $storeTypes->count() }} types</p>
                    </div>

                    <div class="p-6">
                        <a href="{{ route('admin.store-types.index') }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl mb-4 text-center transition-colors">
                            Manage Store Types
                        </a>

                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            @forelse($storeTypes as $type)
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                                            {{ substr($type->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-stone-900">{{ $type->name }}</p>
                                            <p class="text-xs text-stone-500">{{ $type->seller_profiles_count ?? 0 }} stores</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-stone-500 py-8">No store types yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    @include('components.bottom-nav')
@endsection