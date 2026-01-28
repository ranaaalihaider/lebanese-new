@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">@trans('Edit Product')</h1>
                    <p class="text-sm text-stone-600 mt-2">
                        <span class="text-stone-500">For:</span>
                        <span
                            class="font-semibold text-emerald-600">{{ $seller->sellerProfile?->store_name ?: $seller->name }}</span>
                    </p>
                </div>
                <a href="{{ route('admin.stores.show', $seller->id) }}"
                    class="text-stone-400 hover:text-stone-600 transition-colors text-2xl leading-none">
                    &times;
                </a>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-stone-700 text-sm font-bold mb-2">@trans('Product Name')</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                        class="shadow-sm border border-stone-300 rounded-lg w-full py-2.5 px-3 text-stone-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-stone-700 text-sm font-bold mb-2">@trans('Category / Type')</label>
                    <select name="product_type_id"
                        class="shadow-sm border border-stone-300 rounded-lg w-full py-2.5 px-3 text-stone-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        required>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name ?: $type->getTranslation('name', 'en') }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_type_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-stone-700 text-sm font-bold mb-2">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}"
                        placeholder="0.00"
                        class="shadow-sm border border-stone-300 rounded-lg w-full py-2.5 px-3 text-stone-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        required>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-stone-700 text-sm font-bold mb-2">@trans('Description')</label>
                    <textarea name="description"
                        class="shadow-sm border border-stone-300 rounded-lg w-full py-2.5 px-3 text-stone-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        rows="4" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-stone-700 text-sm font-bold mb-2">@trans('Product Photos')</label>
                    @if($product->photos->count() > 0)
                        <div class="flex gap-2 mb-3 overflow-x-auto pb-2">
                            @foreach($product->photos as $photo)
                                <div class="w-20 h-20 relative flex-shrink-0 rounded-lg overflow-hidden border-2 border-stone-200">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="photos[]" multiple accept="image/*" class="block w-full text-sm text-stone-500 
                                           file:mr-4 file:py-2.5 file:px-4 
                                           file:rounded-lg file:border-0 
                                           file:text-sm file:font-semibold 
                                           file:bg-emerald-50 file:text-emerald-700 
                                           hover:file:bg-emerald-100 
                                           border border-stone-300 rounded-lg
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-stone-500 mt-2">@trans('Upload new photos to add to existing ones.')</p>
                    @error('photos.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                        @trans('Update') @trans('Product')
                    </button>

                    <button type="button"
                        onclick="if(confirm('@trans('Are you sure you want to delete this product?')')) { document.getElementById('delete-form').submit(); }"
                        class="bg-red-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                        @trans('Delete')
                    </button>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="delete-form" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection