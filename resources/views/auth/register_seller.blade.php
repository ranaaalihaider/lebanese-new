@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-stone-50">
        <!-- Back Button -->
        <a href="{{ route('login') }}"
            class="absolute top-6 left-6 p-2 bg-white rounded-full shadow-sm text-stone-400 hover:text-emerald-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>

        <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl p-8 border border-stone-100">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-emerald-900">@trans('Become a Partner')</h2>
                <p class="text-stone-500 text-sm">@trans('Start selling directly to customers')</p>
            </div>

            <form method="POST" action="{{ route('register.seller') }}" class="space-y-6">
                @csrf

                <!-- Personal Info Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-1 mb-2">@trans('Personal Information')
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Full Name')</label>
                            <input type="text" name="name"
                                class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                                required autocomplete="name">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Phone Number')</label>
                            <input type="tel" name="phone"
                                class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                                required autocomplete="tel">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Store Info Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-1 mb-2 pt-2">@trans('Store Details')</h3>

                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Store Name')</label>
                        <input type="text" name="store_name"
                            class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Store Tagline (Optional)</label>
                        <input type="text" name="store_tagline" placeholder="e.g. Authentic Lebanese Cuisine"
                            class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Category')</label>
                            <select name="store_type_id"
                                class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                                required onchange="toggleCustomStoreType(this)">
                                <option value="">@trans('Select...')</option>
                                @foreach($storeTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                                <option value="other">Other (Add New)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Language')</label>
                            <select name="language_preference"
                                class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                                required>
                                <option value="EN">@trans('English')</option>
                                <option value="AR">Arabic (العربية)</option>
                                <option value="HY">Armenian (Հայերեն)</option>
                                <option value="FR">French (Français)</option>
                            </select>
                        </div>
                    </div>

                    <div class="hidden" id="custom_store_type_container">
                        <label class="block text-xs font-bold text-stone-500 mb-1">@trans('New Category Name')</label>
                        <input type="text" name="custom_store_type" id="custom_store_type"
                            class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Pickup Location (Area Only)</label>
                        <input type="text" name="pickup_location" placeholder="e.g. Beirut, Hamra"
                            class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                            required>
                        <p class="text-xs text-stone-400 mt-1 pl-1">@trans('City or Area name only. No full address needed.')</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Latitude')</label>
                            <input type="text" name="latitude" placeholder="e.g. 33.8938"
                                class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">@trans('Longitude')</label>
                            <input type="text" name="longitude" placeholder="e.g. 35.5018"
                                class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-xl text-stone-900 focus:ring-2 focus:ring-emerald-500 transition-all"
                                required>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleCustomStoreType(select) {
                        const container = document.getElementById('custom_store_type_container');
                        const input = document.getElementById('custom_store_type');
                        if (select.value === 'other') {
                            container.classList.remove('hidden');
                            input.required = true;
                        } else {
                            container.classList.add('hidden');
                            input.required = false;
                        }
                    }
                </script>

                <button type="submit"
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95 mt-8">
                    @trans('Submit Application')
                </button>
            </form>

            <div class="mt-8 text-center pt-4 border-t border-stone-100">
                <a href="{{ route('login') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-800">Back to
                    Login</a>
            </div>
        </div>
    </div>
@endsection