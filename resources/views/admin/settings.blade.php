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

                <!-- WhatsApp Number -->
                <div class="mb-6 pb-6 border-b border-stone-200">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        @trans('WhatsApp Number')
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <input type="text" name="whatsapp_number"
                            value="{{ $settings['whatsapp_number'] ?? '' }}"
                            placeholder="+961 XX XXX XXX"
                            class="shadow appearance-none border rounded w-full py-2 pl-10 pr-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-green-500">
                    </div>
                    <p class="text-xs text-stone-500 mt-2">
                        @trans('Enter WhatsApp number with country code (e.g., +961 XX XXX XXX). This will be used for customer support.')
                    </p>
                    @error('whatsapp_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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