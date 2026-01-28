@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">@trans('Add New Product')</h1>
                <a href="{{ route('seller.products.index') }}" class="text-gray-500 hover:text-gray-700">@trans('&times; Cancel')</a>
            </div>

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Product Name')</label>
                    <input type="text" name="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Category / Type')</label>
                    <select name="product_type_id"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Price ($)</label>
                    <input type="number" step="0.01" name="price"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                {{-- Payment Methods Removed: Controlled globally by Admin --}}

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Description')</label>
                    <textarea name="description"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        rows="3"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Photos (Select multiple)</label>
                    <input type="file" name="photos[]" multiple
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-stone-400 mt-1">@trans('You can select more than one photo.')</p>
                </div>

                <button type="submit"
                    class="bg-blue-600 text-white font-bold py-2 px-4 rounded w-full hover:bg-blue-700">Save
                    Product</button>
            </form>
        </div>
    </div>
@endsection