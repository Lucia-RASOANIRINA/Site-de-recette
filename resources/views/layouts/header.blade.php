<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OURA TABLE | L'assiette ouverte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Petit ajout pour une transition fluide du menu mobile */
        #mobile-menu {
            transition: all 0.3s ease-in-out;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#fcfaf8] font-sans">

    <nav class="bg-white shadow-sm fixed w-full z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-baseline gap-3 shrink-0">
                    <div class="text-2xl font-black text-gray-800 tracking-tighter">
                        OURA<span class="text-orange-500">TABLE</span>
                    </div>
                    <span class="hidden lg:block text-xs font-medium text-gray-400 uppercase tracking-widest border-l pl-3 border-gray-200">
                        L'assiette ouverte
                    </span>
                </div>

                <div class="hidden md:flex flex-1 max-w-xs mx-10">
                    <div class="relative w-full">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="text" placeholder="Rechercher..." class="w-full bg-gray-100 rounded-full py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all">
                    </div>
                </div>

                <div class="hidden md:flex space-x-8 items-center font-bold text-xs tracking-wide text-gray-600">
                    <a href="/" class="group flex items-center gap-2 hover:text-orange-500 transition-colors">
                        <i data-lucide="utensils" class="w-4 h-4 text-gray-400 group-hover:text-orange-500"></i> 
                        RECETTES
                    </a>
                    <a href="/community" class="group flex items-center gap-2 hover:text-orange-500 transition-colors">
                        <i data-lucide="users" class="w-4 h-4 text-gray-400 group-hover:text-orange-500"></i> 
                        COMMUNAUTÉ
                    </a>
                    <a href="{{ route('login') }}" class="bg-orange-500 text-white px-6 py-2.5 rounded-full hover:bg-orange-600 hover:shadow-lg hover:shadow-orange-200 transition-all duration-300">
                        CONNEXION
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-600 p-2">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg">
            <div class="px-4 py-6 space-y-4">
                <a href="/recipes" class="flex items-center gap-3 font-bold text-sm text-gray-600 hover:text-orange-500">
                    <i data-lucide="utensils" class="w-5 h-5"></i> RECETTES
                </a>
                <a href="/community" class="flex items-center gap-3 font-bold text-sm text-gray-600 hover:text-orange-500">
                    <i data-lucide="users" class="w-5 h-5"></i> COMMUNAUTÉ
                </a>
                <div class="pt-2 border-t border-gray-50">
                    <a href="/login" class="block w-full bg-orange-500 text-white text-center py-3 rounded-full font-bold shadow-md">
                        CONNEXION
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 px-4">
        @yield('content')
    </main>

    <script >
        // Initialisation des icônes
        lucide.createIcons();

        // Logique du menu mobile
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>