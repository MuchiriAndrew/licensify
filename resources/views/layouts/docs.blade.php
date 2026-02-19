@extends('layouts.app')

@section('title', 'Documentation')

@push('styles')
<style>html { scroll-behavior: smooth; }</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
        {{-- Sidebar --}}
        <aside class="lg:w-64 shrink-0 order-2 lg:order-1">
            <nav class="lg:sticky lg:top-24 space-y-1 text-sm" aria-label="Documentation">
                <p class="font-semibold text-gray-900 uppercase tracking-wider text-xs mb-4">On this page</p>
                <a href="{{ url('/docs') }}#overview" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Overview</a>
                <a href="{{ url('/docs') }}#api-reference" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">API Reference</a>
                <span class="block pl-4 mt-2 space-y-1 border-l-2 border-gray-200 ml-2">
                    <a href="{{ url('/docs') }}#validate-license" class="block py-1 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Validate license</a>
                    <a href="{{ url('/docs') }}#request" class="block py-1 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Request</a>
                    <a href="{{ url('/docs') }}#responses" class="block py-1 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Responses</a>
                    <a href="{{ url('/docs') }}#behavior" class="block py-1 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Behavior</a>
                    <a href="{{ url('/docs') }}#examples" class="block py-1 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Examples</a>
                    <a href="{{ url('/docs') }}#other-routes" class="block py-1 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2">Other routes</a>
                </span>
                <a href="{{ url('/docs') }}#what-works" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium mt-4">What works today</a>
                <a href="{{ url('/docs') }}#implemented" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">Implemented</a>
                <a href="{{ url('/docs') }}#remaining-gaps" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">Remaining gaps</a>
                <a href="{{ url('/docs') }}#nice-to-have" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">Nice-to-have</a>
                <a href="{{ url('/docs') }}#wordpress-checklist" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">WordPress integration</a>
                <a href="{{ url('/docs') }}#priority" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">Priority order</a>
                <a href="{{ url('/docs') }}#conclusion" class="block py-1.5 text-gray-600 hover:text-brand-600 rounded px-2 -mx-2 font-medium">Conclusion</a>
            </nav>
        </aside>

        {{-- Main content --}}
        <div class="min-w-0 flex-1 max-w-3xl order-1 lg:order-2">
            @yield('docs_content')
        </div>
    </div>
</div>
@endsection
