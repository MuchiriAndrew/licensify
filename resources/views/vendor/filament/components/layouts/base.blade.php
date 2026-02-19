@props([
    'title' => null,
])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament::layout.direction') ?? 'ltr' }}"
    class="filament js-focus-visible min-h-screen antialiased"
>
    <head>
        {{ \Filament\Facades\Filament::renderHook('head.start') }}

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @foreach (\Filament\Facades\Filament::getMeta() as $tag)
            {{ $tag }}
        @endforeach

        @if ($favicon = config('filament.favicon'))
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        <title>
            {{ $title ? "{$title} - " : null }} {{ config('filament.brand') }}
        </title>

        {{ \Filament\Facades\Filament::renderHook('styles.start') }}

        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }

            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }

            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }

            :root {
                --sidebar-width: {{ config('filament.layout.sidebar.width') ?? '20rem' }};
                --collapsed-sidebar-width: {{ config('filament.layout.sidebar.collapsed_width') ?? '5.4rem' }};
                /* Licensify brand (match website) */
                --primary-50: #f0f9ff;
                --primary-100: #e0f2fe;
                --primary-200: #bae6fd;
                --primary-300: #7dd3fc;
                --primary-400: #38bdf8;
                --primary-500: #0ea5e9;
                --primary-600: #0284c7;
                --primary-700: #0369a1;
                --primary-800: #075985;
                --primary-900: #0c4a6e;
            }
            /* Licensify brand: override Tailwind primary to match website */
            .text-primary-500 { color: #0ea5e9 !important; }
            .text-primary-600 { color: #0284c7 !important; }
            .text-primary-700 { color: #0369a1 !important; }
            .bg-primary-500 { background-color: #0ea5e9 !important; }
            .bg-primary-600 { background-color: #0284c7 !important; }
            .bg-primary-50 { background-color: #f0f9ff !important; }
            .hover\:bg-primary-700:hover { background-color: #0369a1 !important; }
            .hover\:text-primary-500:hover { color: #0ea5e9 !important; }
            .focus\:border-primary-500:focus { border-color: #0ea5e9 !important; }
            .focus\:ring-primary-500:focus { --tw-ring-color: #0ea5e9 !important; }
            .ring-primary-500 { --tw-ring-color: #0ea5e9 !important; }
            .border-primary-500 { border-color: #0ea5e9 !important; }
        </style>

        @livewireStyles

        @if (filled($fontsUrl = config('filament.google_fonts')))
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link
                rel="preconnect"
                href="https://fonts.gstatic.com"
                crossorigin
            />
            <link href="{{ $fontsUrl }}" rel="stylesheet" />
        @endif

        @foreach (\Filament\Facades\Filament::getStyles() as $name => $path)
            @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
                <link rel="stylesheet" href="{{ $path }}" />
            @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
                {!! $path !!}
            @else
                <link
                    rel="stylesheet"
                    href="{{
                        route('filament.asset', [
                            'file' => "{$name}.css",
                        ])
                    }}"
                />
            @endif
        @endforeach

        {{ \Filament\Facades\Filament::getThemeLink() }}

        {{ \Filament\Facades\Filament::renderHook('styles.end') }}

        @if (config('filament.dark_mode'))
            <script>
                const theme = localStorage.getItem('theme')

                if (
                    theme === 'dark' ||
                    (!theme &&
                        window.matchMedia('(prefers-color-scheme: dark)')
                            .matches)
                ) {
                    document.documentElement.classList.add('dark')
                }
            </script>
        @endif

        {{ \Filament\Facades\Filament::renderHook('head.end') }}
    </head>

    <body
        @class([
            'filament-body min-h-screen overflow-y-auto bg-gray-100 text-gray-900',
            'dark:bg-gray-900 dark:text-gray-100' => config('filament.dark_mode'),
        ])
    >
        {{ \Filament\Facades\Filament::renderHook('body.start') }}

        {{ $slot }}

        {{ \Filament\Facades\Filament::renderHook('scripts.start') }}

        @livewireScripts

        <script>
            window.filamentData = @json(\Filament\Facades\Filament::getScriptData())
        </script>

        @foreach (\Filament\Facades\Filament::getBeforeCoreScripts() as $name => $path)
            @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
                <script defer src="{{ $path }}"></script>
            @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
                {!! $path !!}
            @else
                <script
                    defer
                    src="{{
                        route('filament.asset', [
                            'file' => "{$name}.js",
                        ])
                    }}"
                ></script>
            @endif
        @endforeach

        @stack('beforeCoreScripts')

        <script
            defer
            src="{{
                route('filament.asset', [
                    'id' => Filament\get_asset_id('app.js'),
                    'file' => 'app.js',
                ])
            }}"
        ></script>

        @if (config('filament.broadcasting.echo'))
            <script
                defer
                src="{{
                    route('filament.asset', [
                        'id' => Filament\get_asset_id('echo.js'),
                        'file' => 'echo.js',
                    ])
                }}"
            ></script>

            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                    window.dispatchEvent(new CustomEvent('EchoLoaded'))
                })
            </script>
        @endif

        @foreach (\Filament\Facades\Filament::getScripts() as $name => $path)
            @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
                <script defer src="{{ $path }}"></script>
            @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
                {!! $path !!}
            @else
                <script
                    defer
                    src="{{
                        route('filament.asset', [
                            'file' => "{$name}.js",
                        ])
                    }}"
                ></script>
            @endif
        @endforeach

        @stack('scripts')

        {{ \Filament\Facades\Filament::renderHook('scripts.end') }}

        {{ \Filament\Facades\Filament::renderHook('body.end') }}
    </body>
</html>
