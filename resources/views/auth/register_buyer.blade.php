@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center py-10 sm:px-6 lg:px-8 bg-stone-50 min-h-[calc(100vh-4rem)] relative">
        <!-- Back Button (Absolute for desktop/mobile consistency) -->
        <a href="{{ route('login') }}"
            class="absolute top-4 left-4 p-2 bg-white rounded-full shadow-sm text-stone-400 hover:text-emerald-600 transition-colors z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-emerald-900 tracking-tight">
                @trans('Create Account')
            </h2>
            <p class="mt-2 text-center text-sm text-stone-600">
                @trans('Join to shop fresh local produce')
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white py-8 px-4 shadow-none sm:shadow-xl sm:rounded-3xl sm:px-10 border-t sm:border border-stone-100">
                <form method="POST" action="{{ route('register.buyer') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-stone-700">@trans('Full Name')</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" required autocomplete="name" placeholder="John Doe"
                                class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-stone-700">@trans('Phone Number')</label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone" required autocomplete="tel" placeholder="70 123 456"
                                class="appearance-none block w-full px-3 py-3 border border-stone-300 rounded-xl shadow-sm placeholder-stone-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95">
                            @trans('Create Account')
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-stone-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-stone-500">
                                @trans('By registering, you agree to our') <a href="#"
                                    class="font-medium text-emerald-600 hover:text-emerald-500">@trans('Terms')</a>
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-500">
                            @trans('Already have an account? Log in')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection