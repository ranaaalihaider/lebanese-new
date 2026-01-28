@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">@trans('Site Settings')</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Logo Upload Section -->
                <div class="mb-6 pb-6 border-b border-stone-200">
                    <label class="block text-stone-700 text-sm font-bold mb-3">@trans('Site Logo')</label>

                    @php
                        $logoPath = \App\Models\Setting::getSetting('site_logo');
                    @endphp

                    @if($logoPath)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $logoPath) }}" alt="Current Logo"
                                class="h-20 object-contain border border-stone-200 rounded-lg p-2 bg-white">
                            <p class="text-xs text-stone-500 mt-2">@trans('Current Logo')</p>
                        </div>
                    @endif

                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-stone-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-emerald-50 file:text-emerald-700
                            hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-xs text-stone-500 mt-2">Upload PNG or JPG (max 2MB). Will be used in navigation and as
                        favicon.</p>

                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Business / Site Name')</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Lebanese Marketplace' }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">@trans('Tagline')</label>
                    <input type="text" name="tagline"
                        value="{{ $settings['tagline'] ?? 'Fresh from the farm to your table' }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Payment Methods -->
                <div class="mb-6 pb-6 border-t border-stone-200 pt-6">
                    <label class="block text-stone-700 text-sm font-bold mb-4">@trans('Global Payment Methods')</label>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="hidden" name="payment_method_cod" value="0">
                            <input type="checkbox" name="payment_method_cod" id="pm_cod" value="1"
                                {{ \App\Models\Setting::getSetting('payment_method_cod') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <label for="pm_cod" class="ml-2 block text-sm text-gray-900">
                                Cash on Delivery (COD)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="payment_method_online" value="0">
                            <input type="checkbox" name="payment_method_online" id="pm_online" value="1"
                                {{ \App\Models\Setting::getSetting('payment_method_online') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <label for="pm_online" class="ml-2 block text-sm text-gray-900">
                                @trans('Full Online Payment')
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="payment_method_partial" value="0">
                            <input type="checkbox" name="payment_method_partial" id="pm_partial" value="1"
                                {{ \App\Models\Setting::getSetting('payment_method_partial') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <label for="pm_partial" class="ml-2 block text-sm text-gray-900">
                                @trans('Partial Online Payment')
                            </label>
                        </div>
                    </div>
                    <p class="text-xs text-stone-500 mt-2">@trans('Selected methods will be available to all buyers at checkout.')</p>
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline">
                    @trans('Save Settings')
                </button>
            </form>
        </div>
    </div>
@endsection