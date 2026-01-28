@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">@trans('Edit Seller Details')</h1>
                <a href="{{ route('admin.sellers') }}" class="text-gray-500 hover:text-gray-700">@trans('&times; Cancel')</a>
            </div>

            <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Personal Info -->
                <div class="mb-6 border-b pb-4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">@trans('Personal Information')</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Full Name')</label>
                            <input type="text" name="name" value="{{ $seller->name }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Phone')</label>
                            <input type="text" name="phone" value="{{ $seller->phone }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Store Info -->
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">@trans('Store Details')</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Store Name')</label>
                        <input type="text" name="store_name" value="{{ $seller->sellerProfile->store_name }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Store Tagline')</label>
                        <input type="text" name="store_tagline" value="{{ $seller->sellerProfile->store_tagline }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="e.g. Best Burgers in Town">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Category')</label>
                            <select name="store_type_id"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                @foreach($storeTypes as $type)
                                    <option value="{{ $type->id }}" {{ $seller->sellerProfile->store_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Language Preference')</label>
                            <select name="language_preference"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="EN" {{ $seller->sellerProfile->language_preference == 'EN' ? 'selected' : '' }}>@trans('English')</option>
                                <option value="AR" {{ $seller->sellerProfile->language_preference == 'AR' ? 'selected' : '' }}>@trans('Arabic')</option>
                                <option value="HY" {{ $seller->sellerProfile->language_preference == 'HY' ? 'selected' : '' }}>@trans('Armenian')</option>
                                <option value="FR" {{ $seller->sellerProfile->language_preference == 'FR' ? 'selected' : '' }}>@trans('French')</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pickup Location (Area Name)</label>
                        <input type="text" name="pickup_location" value="{{ $seller->sellerProfile->pickup_location }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Latitude')</label>
                            <input type="text" name="latitude" value="{{ $seller->sellerProfile->latitude }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Longitude')</label>
                            <input type="text" name="longitude" value="{{ $seller->sellerProfile->longitude }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline">Update
                        Seller Details</button>
                </div>
            </form>
        </div>
    </div>
@endsection