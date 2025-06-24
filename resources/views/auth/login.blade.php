@extends('layouts.simple')

@section('title', 'Login')

@section('content')

@include('layouts.navbar')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-12">
    <div class="bg-white bg-opacity-90 backdrop-blur-lg shadow-2xl rounded-2xl max-w-md w-full p-8 space-y-8 border border-gray-200">
        <div class="text-center">
            <h2 class="text-3xl font-semibold text-gray-900">Sign in to Library System</h2>
            <p class="mt-2 text-sm text-gray-500">Manage your books and borrowings</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-red-700">Login Failed</h3>
                    <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required
                        value="{{ old('email') }}"
                        placeholder="your@gmail.com"
                        style="border: 2px solid #d1d5db !important; border-radius: 0.75rem !important;"
                        class="mt-1 w-full px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                        placeholder="••••••••"
                        style="border: 2px solid #d1d5db !important; border-radius: 0.75rem !important;"
                        class="mt-1 w-full px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember"
                           style="border: 1px solid #d1d5db !important;"
                           class="rounded mr-2">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                    Forgot password?
                </a>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-xl shadow-md transition duration-300">
                Sign in
            </button>

            <p class="text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register here</a>
            </p>
        </form>
    </div>
</div>
@endsection
