@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center py-10 sm:px-6 lg:px-8 bg-stone-50 min-h-[calc(100vh-4rem)] relative">
        <!-- Back Button -->
        <a href="{{ route('login') }}"
            class="absolute top-4 left-4 p-2 bg-white rounded-full shadow-sm text-stone-400 hover:text-emerald-600 transition-colors z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>

        <div class="sm:mx-auto sm:w-full sm:max-w-lg">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-emerald-900 tracking-tight">
                @trans('Become a Partner')
            </h2>
            <p class="mt-2 text-center text-sm text-stone-600">
                @trans('Start selling directly to customers')
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <div
                class="bg-white py-8 px-4 shadow-none sm:shadow-xl sm:rounded-3xl sm:px-10 border-t sm:border border-stone-100">
                <form method="POST" action="{{ route('register.seller') }}" class="space-y-6">
                    @csrf

                    <!-- Personal Info Section -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-1 mb-2">
                            @trans('Personal Information')</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-stone-700">@trans('Full Name')</label>
                                <div class="mt-1">
                                    <input type="text" name="name" required autocomplete="name"
                                        class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700">@trans('Phone Number')</label>
                                <div class="mt-1">
                                    <input type="tel" name="phone" required autocomplete="tel"
                                        class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                </div>
                                @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Store Info Section -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-1 mb-2 pt-2">
                            @trans('Store Details')</h3>

                        <div>
                            <label class="block text-sm font-medium text-stone-700">@trans('Store Name')</label>
                            <div class="mt-1">
                                <input type="text" name="store_name" required
                                    class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700">Store Tagline (Optional)</label>
                            <div class="mt-1">
                                <input type="text" name="store_tagline" placeholder="e.g. Authentic Lebanese Cuisine"
                                    class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-stone-700">@trans('Category')</label>
                                <div class="mt-1">
                                    <select name="store_type_id" required onchange="toggleCustomStoreType(this)"
                                        class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        <option value="">@trans('Select...')</option>
                                        @foreach($storeTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                        <option value="other">Other (Add New)</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700">@trans('Language')</label>
                                <div class="mt-1">
                                    <select name="language_preference" required
                                        class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        <option value="EN">@trans('English')</option>
                                        <option value="AR">Arabic (العربية)</option>
                                        <option value="HY">Armenian (Հայերեն)</option>
                                        <option value="FR">French (Français)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="hidden" id="custom_store_type_container">
                            <label class="block text-sm font-medium text-stone-700">@trans('New Category Name')</label>
                            <div class="mt-1">
                                <input type="text" name="custom_store_type" id="custom_store_type"
                                    class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700">Pickup Location (Area Only)</label>
                            <div class="mt-1">
                                <input type="text" name="pickup_location" placeholder="e.g. Beirut, Hamra" required
                                    class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            </div>
                            <p class="text-xs text-stone-500 mt-1">@trans('City or Area name only. No full address needed.')
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-stone-700">@trans('Latitude')</label>
                                <div class="mt-1">
                                    <input type="text" name="latitude" placeholder="e.g. 33.8938" required
                                        class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700">@trans('Longitude')</label>
                                <div class="mt-1">
                                    <input type="text" name="longitude" placeholder="e.g. 35.5018" required
                                        class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                </div>
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
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95 mt-8">
                        @trans('Submit Application')
                    </button>
                </form>

                <div class="mt-6 text-center pt-4 border-t border-stone-100">
                    <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-500">
                        @trans('Back to Login')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection