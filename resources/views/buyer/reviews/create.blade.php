@extends('layouts.app')

@section('content')
    <div class="bg-stone-50 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center gap-6 mb-8 border-b border-stone-100 pb-8">
                        <div class="w-24 h-24 bg-stone-100 rounded-xl overflow-hidden flex-shrink-0">
                            @if($order->product->thumbnail)
                                <img src="{{ asset('storage/' . $order->product->thumbnail) }}"
                                    alt="{{ $order->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl">📦</div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-stone-900">@trans('Write a Review')</h1>
                            <p class="text-stone-500 mt-1">{{ $order->product->name }}</p>
                            <p class="text-sm text-stone-400 mt-2">Order #{{ $order->order_number }}</p>
                        </div>
                    </div>

                    <form action="{{ route('buyer.reviews.store', $order) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-2">@trans('Overall Rating')</label>
                            <div class="flex items-center gap-1" x-data="{ rating: 0, hover: 0 }">
                                <input type="hidden" name="rating" :value="rating">
                                <template x-for="i in 5">
                                    <button type="button" @click="rating = i" @mouseover="hover = i" @mouseleave="hover = 0"
                                        class="focus:outline-none transition-transform hover:scale-110 p-1">
                                        <svg class="w-10 h-10"
                                            :class="(hover || rating) >= i ? 'text-yellow-400 fill-current' : 'text-stone-300 fill-none'"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </button>
                                </template>
                                <span class="ml-3 text-sm font-medium text-stone-500"
                                    x-text="rating ? rating + ' out of 5' : 'Select a rating'"></span>
                            </div>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-stone-700 mb-2">@trans('Your Review')</label>
                            <textarea name="comment" id="comment" rows="4"
                                class="w-full rounded-xl border-stone-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-stone-400"
                                placeholder="What did you like or dislike? How was the quality?"></textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo Upload (Simplified) -->
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-2">@trans('Add a Photo')</label>

                            <div class="flex items-center gap-4">
                                <div
                                    class="relative group w-24 h-24 bg-stone-100 rounded-xl border-2 border-dashed border-stone-300 flex items-center justify-center overflow-hidden hover:border-emerald-500 transition-colors">
                                    <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                    <div id="upload-placeholder" class="text-center p-2">
                                        <svg class="w-6 h-6 mx-auto text-stone-400 group-hover:text-emerald-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span class="text-xs text-stone-500 block mt-1">@trans('Add')</span>
                                    </div>
                                    <input type="file" name="image" id="image"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"
                                        onchange="previewFile(this)">
                                </div>
                                <div class="text-sm text-stone-500">
                                    <p class="font-medium text-stone-700">@trans('Upload an image')</p>
                                    <p class="text-xs mt-1">@trans('JPG, PNG up to 5MB')</p>
                                </div>
                            </div>

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Script for Preview -->
                        <script>
                            function previewFile(input) {
                                const preview = document.getElementById('image-preview');
                                const placeholder = document.getElementById('upload-placeholder');
                                const file = input.files[0];

                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        preview.src = e.target.result;
                                        preview.classList.remove('hidden');
                                        placeholder.classList.add('hidden');
                                    }
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = '#';
                                    preview.classList.add('hidden');
                                    placeholder.classList.remove('hidden');
                                }
                            }
                        </script>


                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('buyer.orders.index') }}"
                                class="text-stone-600 hover:text-stone-900 font-medium">@trans('Cancel')</a>
                            <button type="submit"
                                class="bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                                @trans('Submit Review')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection