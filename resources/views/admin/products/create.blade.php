@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">@trans('Add New Product')</h1>
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

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                <input type="hidden" name="seller_id" value="{{ $seller->id }}">

                <div>
                    <label class="block text-stone-700 text-sm font-bold mb-2">@trans('Product Name')</label>
                    <input type="text" name="name" value="{{ old('name') }}"
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
                        <option value="">@trans('Select...')</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>
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
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00"
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
                        rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-stone-700 text-sm font-bold mb-2">@trans('Product Photos (Select multiple)')</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="block w-full text-sm text-stone-500 
                                           file:mr-4 file:py-2.5 file:px-4 
                                           file:rounded-lg file:border-0 
                                           file:text-sm file:font-semibold 
                                           file:bg-emerald-50 file:text-emerald-700 
                                           hover:file:bg-emerald-100 
                                           border border-stone-300 rounded-lg
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-stone-500 mt-2">@trans('You can select more than one photo.')</p>
                    @error('photos.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg w-full hover:bg-emerald-700 transition-colors shadow-sm">
                    @trans('Save') @trans('Product')
                </button>
            </form>
        </div>
    </div>
@endsection