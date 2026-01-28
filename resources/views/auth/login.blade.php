@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center py-10 sm:px-6 lg:px-8 bg-stone-50 min-h-[calc(100vh-4rem)]">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="mt-6 text-center text-3xl font-extrabold text-emerald-900 tracking-tight">
                @trans('Welcome Back')
            </h1>
            <p class="mt-2 text-center text-sm text-stone-600">
                @trans('Sign in to access your marketplace')
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white py-8 px-4 shadow-none sm:shadow-xl sm:rounded-3xl sm:px-10 border-t sm:border border-stone-100">
                @auth
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="mx-auto h-12 w-12 bg-emerald-100 rounded-full flex items-center justify-center mb-3">
                                <span class="text-emerald-600 font-bold text-xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-lg font-medium text-stone-900">@trans('Hi'), {{ Auth::user()->name }}</h3>
                            <p class="text-sm text-stone-500">@trans('You are already logged in.')</p>
                        </div>

                        <a href="{{ route('dashboard') }}"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                            @trans('Go to Dashboard')
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="text-sm text-stone-500 hover:text-red-600 font-medium transition-colors">
                                @trans('Sign out')
                            </button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="phone" class="block text-sm font-medium text-stone-700">@trans('Phone Number')</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-stone-500 sm:text-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </span>
                                </div>
                                <input type="tel" name="phone" id="phone" required autocomplete="tel" autofocus
                                    class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 h-12 text-lg sm:text-sm border-stone-300 rounded-xl"
                                    placeholder="70 123 456">
                            </div>
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95">
                                @trans('Send Verification Code')
                            </button>
                        </div>
                    </form>

                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-stone-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-stone-500">@trans('New around here?')</span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-3">
                            <a href="{{ route('register.buyer') }}"
                                class="w-full flex items-center justify-center px-4 py-3 border border-stone-200 rounded-xl shadow-sm text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 transition-colors">
                                @trans('Create Buyer Account')
                            </a>
                            <a href="{{ route('register.seller') }}"
                                class="w-full flex items-center justify-center px-4 py-3 border border-emerald-100 rounded-xl shadow-sm text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                                @trans('Become a Seller')
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection