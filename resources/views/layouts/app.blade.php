<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Licensify') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|fira-code:300,400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'], mono: ['Fira Code', 'monospace'] },



                    colors: {
                        brand: { 50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e' },
                        accent: '#f97316'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-mesh { background: radial-gradient(at 40% 20%, rgba(14, 165, 233, 0.15) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(249, 115, 22, 0.1) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(14, 165, 233, 0.08) 0px, transparent 50%); }
        .glow { box-shadow: 0 0 60px -15px rgba(14, 165, 233, 0.4); }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 min-h-screen" id="app-body">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-14 sm:h-16">
            <a href="{{ url('/') }}" class="text-lg sm:text-xl font-semibold text-gray-900 tracking-tight">
                <span class="text-brand-600">Licensify</span>
            </a>
            {{-- Desktop nav --}}
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Home</a>
                <a href="{{ url('/docs') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Docs</a>
                @auth
                    <a href="{{ url('/admin') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 transition">Dashboard</a>
                @else
                    <a href="{{ url('/register') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Sign up</a>
                    <a href="{{ url('/admin') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 transition">Log in</a>
                @endauth
            </div>
            {{-- Mobile menu button --}}
            <button type="button" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition aria-label="Open menu" aria-expanded="false" aria-controls="nav-offcanvas" id="nav-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- Offcanvas overlay --}}
    <div id="nav-overlay" class="fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" aria-hidden="true"></div>

    {{-- Offcanvas drawer --}}
    <aside id="nav-offcanvas" class="fixed top-0 right-0 bottom-0 w-72 max-w-[85vw] bg-white shadow-xl z-50 flex flex-col transform translate-x-full transition-transform duration-300 ease-out lg:hidden" aria-label="Navigation menu">
        <div class="flex items-center justify-between h-14 px-4 ">
            <span class="text-lg font-semibold text-gray-900"></span>
            <button type="button" class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition aria-label="Close menu" id="nav-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-4 px-4 space-y-1">
            <a href="{{ url('/') }}" class="nav-drawer-link block py-3 px-3 rounded-lg text-gray-700 hover:bg-brand-50 hover:text-brand-700 font-medium transition">Home</a>
            <a href="{{ url('/docs') }}" class="nav-drawer-link block py-3 px-3 rounded-lg text-gray-700 hover:bg-brand-50 hover:text-brand-700 font-medium transition">Docs</a>
            @auth
                <a href="{{ url('/admin') }}" class="nav-drawer-link block py-3 px-3 rounded-lg text-brand-600 hover:bg-brand-50 font-medium transition">Dashboard</a>
            @else
                <a href="{{ url('/register') }}" class="nav-drawer-link block py-3 px-3 rounded-lg text-gray-700 hover:bg-brand-50 hover:text-brand-700 font-medium transition">Sign up</a>
                <a href="{{ url('/admin') }}" class="nav-drawer-link block py-3 px-3 rounded-lg bg-brand-600 text-white font-medium text-center hover:bg-brand-700 transition">Log in</a>
            @endauth
        </div>
    </aside>

    <script>
        (function() {
            var body = document.getElementById('app-body');
            var btn = document.getElementById('nav-menu-btn');
            var closeBtn = document.getElementById('nav-close-btn');
            var overlay = document.getElementById('nav-overlay');
            var offcanvas = document.getElementById('nav-offcanvas');
            var links = document.querySelectorAll('.nav-drawer-link');

            function openNav() {
                offcanvas.classList.remove('translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100');
                btn.setAttribute('aria-expanded', 'true');
                document.documentElement.style.overflow = 'hidden';
            }
            function closeNav() {
                offcanvas.classList.add('translate-x-full');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100');
                btn.setAttribute('aria-expanded', 'false');
                document.documentElement.style.overflow = '';
            }

            if (btn) btn.addEventListener('click', openNav);
            if (closeBtn) closeBtn.addEventListener('click', closeNav);
            if (overlay) overlay.addEventListener('click', closeNav);
            links.forEach(function(link) { link.addEventListener('click', closeNav); });
        })();
    </script>

    <main class="pt-14 sm:pt-16">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 mt-24 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Validate licenses via API.
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
