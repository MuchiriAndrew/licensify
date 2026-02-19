@extends('layouts.app')

@section('title', 'Sign up')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <h1 class="text-2xl font-bold text-gray-900 text-center">Create your account</h1>
        <p class="mt-2 text-gray-600 text-center text-sm">Start creating license keys for your products.</p>

        <form method="POST" action="{{ url('/register') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 @error('name') border-red-500 @enderror" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 @error('email') border-red-500 @enderror" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 @error('password') border-red-500 @enderror" />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500" />
            </div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition">
                Sign up
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account? <a href="{{ url('/admin') }}" class="text-brand-600 font-medium hover:underline">Log in</a>
        </p>
    </div>
</div>
@endsection
