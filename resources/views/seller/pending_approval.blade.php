@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen p-6 text-center">
        <div class="bg-yellow-100 p-6 rounded-full mb-6">
            <svg class="w-16 h-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold mb-2">Account Pending</h1>
        <p class="text-gray-600 mb-6">Your seller account is currently under review by the administrator. Please check back
            later.</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-blue-500 font-bold hover:underline">Return support / Logout</button>
        </form>
    </div>
@endsection