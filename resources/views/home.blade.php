@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="gradient-mesh min-h-[90vh] flex flex-col items-center justify-center px-4 pt-16">
    <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-900 tracking-tight leading-tight">
            <span class="text-brand-600">Licensify</span> your software
            <span class="block mt-1 sm:mt-0 sm:inline">with one API</span>
        </h1>
        <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
            Create license keys, validate them by domain, and see activation history. Perfect for WordPress plugins, SaaS, or any product you sell.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition glow">
                Get started — free
            </a>
            <a href="{{ url('/docs') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-brand-300 hover:text-brand-700 transition">
                Read the docs
            </a>
        </div>
        <p class="mt-6 text-sm text-gray-500">
            Already have an account? <a href="{{ url('/admin') }}" class="text-brand-600 font-medium hover:underline">Log in to dashboard</a>
        </p>
    </div>

    <div class="mt-24 w-full max-w-4xl mx-auto grid sm:grid-cols-3 gap-6 px-4">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600 font-mono text-xl font-bold">1</div>
            <h3 class="mt-4 font-semibold text-gray-900">Create account</h3>
            <p class="mt-2 text-gray-600 text-sm">Sign up and open the dashboard to create license keys.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600 font-mono text-xl font-bold">2</div>
            <h3 class="mt-4 font-semibold text-gray-900">Generate keys</h3>
            <p class="mt-2 text-gray-600 text-sm">Generate keys, set expiry and optional domain lock.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600 font-mono text-xl font-bold">3</div>
            <h3 class="mt-4 font-semibold text-gray-900">Validate via API</h3>
            <p class="mt-2 text-gray-600 text-sm">Your app calls <code class="font-mono text-xs bg-gray-100 px-1 rounded">POST /api/validate-license</code>.</p>
        </div>
    </div>
</div>
@endsection
