@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-stone-50">
        <div class="w-full max-w-sm bg-white rounded-3xl shadow-xl p-8 border border-stone-100">
            <!-- Logo/Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-emerald-800 tracking-tight mb-2">@trans('Welcome Back')</h1>
                <p class="text-stone-500 text-sm">@trans('Sign in to access your marketplace')</p>
            </div>

            @auth
                <div class="mb-6 text-center">
                    <p class="text-stone-600 mb-4">@trans('You are already logged in as') <strong>{{ Auth::user()->name }}</strong></p>
                    <a href="{{ route('dashboard') }}"
                        class="block w-full py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-all">
                        @trans('Go to Dashboard')
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="text-sm text-stone-400 hover:text-red-500 font-medium">
                            @trans('or Logout')
                        </button>
                    </form>
                </div>
            @else

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="phone" class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Phone
                            Number</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </span>
                            <input type="tel" name="phone" id="phone"
                                class="block w-full pl-11 pr-4 py-4 bg-stone-50 border-0 rounded-2xl text-stone-900 placeholder-stone-400 focus:ring-2 focus:ring-emerald-500 transition-all font-medium"
                                placeholder="70 123 456" required autocomplete="tel" autofocus>
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-2 pl-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95">
                        @trans('Send Verification Code')
                    </button>
                </form>

                <div class="mt-10 relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-stone-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-stone-400">@trans('New around here?')</span>
                    </div>
                </div>

            @endauth

            <div class="mt-6 flex flex-col gap-3">
                <a href="{{ route('register.buyer') }}"
                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-stone-100 rounded-2xl shadow-sm text-sm font-bold text-stone-600 bg-white hover:bg-stone-50 hover:border-stone-200 transition-all">
                    @trans('Create Buyer Account')
                </a>
                <a href="{{ route('register.seller') }}"
                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-stone-100 rounded-2xl shadow-sm text-sm font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:border-emerald-200 transition-all">
                    @trans('Become a Seller')
                </a>
            </div>
        </div>
    </div>
@endsection