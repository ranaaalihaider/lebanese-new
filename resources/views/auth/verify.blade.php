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

        <div class="w-full max-w-sm bg-white rounded-3xl shadow-xl p-8 border border-stone-100 text-center">
            <div class="mb-8 flex justify-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-emerald-900 mb-2">Verify it's you</h2>
            <p class="text-stone-500 text-sm mb-8">We sent a 4-digit code to <br /><span
                    class="font-bold text-emerald-700">{{ $phone }}</span></p>

            <form method="POST" action="{{ route('verify') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">

                <div class="flex justify-center">
                    <input type="text" name="otp_code" id="otp_code"
                        class="block w-48 text-center text-3xl font-bold tracking-[0.5em] py-4 bg-stone-50 border-2 border-stone-200 rounded-2xl text-emerald-800 focus:ring-0 focus:border-emerald-500 focus:bg-white transition-all caret-emerald-500"
                        placeholder="••••" maxlength="4" inputmode="numeric" required autocomplete="one-time-code"
                        autofocus>
                </div>
                @error('otp_code')
                    <p class="text-red-500 text-sm font-medium">{{ $message }}</p>
                @enderror

                <button type="submit"
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95">
                    Verify & Continue
                </button>
            </form>

            <div class="mt-8">
                <p class="text-xs text-stone-400">Didn't receive the code?</p>
                <form method="POST" action="{{ route('login') }}" class="inline">
                    @csrf
                    <input type="hidden" name="phone" value="{{ $phone }}">
                    <button type="submit" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 mt-2">Resend
                        Code</button>
                </form>
            </div>
        </div>
    </div>
@endsection