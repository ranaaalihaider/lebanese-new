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

        <div class="w-full max-w-sm bg-white rounded-3xl shadow-xl p-8 border border-stone-100">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-emerald-900">Create Account</h2>
                <p class="text-stone-500 text-sm">Join to shop fresh local produce</p>
            </div>

            <form method="POST" action="{{ route('register.buyer') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Full
                        Name</label>
                    <input type="text" name="name" id="name"
                        class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-2xl text-stone-900 placeholder-stone-400 focus:ring-2 focus:ring-emerald-500 transition-all font-medium"
                        placeholder="John Doe" required autocomplete="name">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Phone
                        Number</label>
                    <input type="tel" name="phone" id="phone"
                        class="block w-full px-4 py-3 bg-stone-50 border-0 rounded-2xl text-stone-900 placeholder-stone-400 focus:ring-2 focus:ring-emerald-500 transition-all font-medium"
                        placeholder="70 123 456" required autocomplete="tel">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-2 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform active:scale-95">
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-stone-400">By registering, you agree to our <a href="#"
                        class="text-emerald-600 hover:underline">Terms of Service</a></p>
                <div class="mt-4 pt-4 border-t border-stone-100">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-800">Already
                        have an account? Log in</a>
                </div>
            </div>
        </div>
    </div>
@endsection