<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') | OURATABLE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif; }
        [x-cloak]{ display:none }
        .admin-nav-link { position: relative; }
        .admin-nav-link::after { content:''; position:absolute; left:0; bottom:-6px; width:0; height:2px; background:#f97316; transition:width .3s ease; }
        .admin-nav-link:hover::after, .admin-nav-link.active::after { width:100%; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800 flex flex-col">

    {{-- En-tête (même style sticky blanc / logo orange que les autres pages) --}}
    <header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/admin" class="flex items-center gap-3 group no-underline">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:rotate-6 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75v3.75m-6 0h12M3 9.75v9a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18.75v-9M3 9.75h18M9 15.75h6" />
                    </svg>
                </div>
                <div class="leading-tight">
                    <span class="text-2xl font-black tracking-tighter italic">Oura<span class="text-orange-500">Table</span></span>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-orange-500 font-bold">Administration</p>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm font-bold uppercase tracking-tight text-gray-600">
                <a href="/admin" class="admin-nav-link flex items-center gap-1.5 hover:text-orange-600 transition-colors {{ request()->is('admin') ? 'active text-orange-600' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Tableau de bord
                </a>
                <a href="/recette-de-la-semaine" class="admin-nav-link flex items-center gap-1.5 hover:text-orange-600 transition-colors {{ request()->is('recette-de-la-semaine') ? 'active text-orange-600' : '' }}">
                    <i data-lucide="trophy" class="w-4 h-4"></i> Recette de la semaine
                </a>
                <a href="/UserHome" class="admin-nav-link flex items-center gap-1.5 hover:text-orange-600 transition-colors {{ request()->is('UserHome') ? 'active text-orange-600' : '' }}">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Voir le site
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <form action="{{ route('recherche') }}" method="GET" class="hidden lg:flex relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher…"
                        class="pl-9 pr-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-orange-500 outline-none w-56">
                </form>
                <div class="w-9 h-9 rounded-full bg-orange-500 text-white flex items-center justify-center text-sm font-bold shrink-0" title="{{ auth()->user()->name }}">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button class="px-3 py-2 text-sm rounded-xl bg-red-50 text-red-600 hover:bg-red-100 font-semibold transition-colors flex items-center gap-1.5">
                        <i data-lucide="log-out" class="w-4 h-4"></i> <span class="hidden sm:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-6">
        @yield('content')
    </main>

    @include('layouts.AdminFooter')

    @include('partials.notifications')

    <script>
        if (window.lucide) lucide.createIcons();
    </script>
    @yield('scripts')
</body>
</html>
