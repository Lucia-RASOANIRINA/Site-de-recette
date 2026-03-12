<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OURA TABLE | L'art de bien manger</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Soulignement animé */
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #f97316;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 99px;
        }
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        /* Animation de flottement pour les icônes au hover */
        .group:hover .lucide-icon {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #f3f4f6; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#fcfaf8] font-sans" 
      x-data="{ 
        mobileMenuOpen: false, 
        searchOpen: false, 
        searchQuery: '',
        currentPath: window.location.pathname 
      }"
      x-init="$watch('mobileMenuOpen', value => { if(value && window.innerWidth > 768) mobileMenuOpen = false }); 
              window.addEventListener('resize', () => { if(window.innerWidth > 768) mobileMenuOpen = false })">

    <nav class="bg-white/90 backdrop-blur-lg fixed w-full z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-4 shrink-0">
                    <a href="/" class="text-2xl font-black text-gray-800 tracking-tighter group flex items-center">
                        OURA<span class="text-orange-500 transition-all duration-300 group-hover:drop-shadow-[0_0_8px_rgba(249,115,22,0.4)]">TABLE</span>
                    </a>
                    <div class="hidden lg:block h-8 w-px bg-gray-200"></div>
                    <span class="hidden lg:block text-[8px] font-extrabold text-gray-600/80 uppercase tracking-[0.4em] italic leading-none">
                        L'art de bien manger
                    </span>
                </div>

                <div class="hidden md:flex flex-1 max-w-sm mx-10">
                    <button @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" 
                            class="w-full bg-gray-100/60 hover:bg-white border border-transparent hover:border-orange-300 text-gray-400 rounded-2xl py-2.5 px-6 text-sm flex items-center gap-3 transition-all duration-300 group hover:shadow-lg hover:shadow-orange-100/50">
                        <i data-lucide="search" class="w-4 h-4 group-hover:text-orange-500 group-hover:scale-110 transition-transform"></i>
                        <span class="font-semibold group-hover:text-gray-600">Rechercher une pépite...</span>
                    </button>
                </div>

                <div class="hidden md:flex space-x-10 items-center font-bold text-[11px] tracking-[0.2em]">
                    <a href="/" 
                       class="relative py-2 flex items-center gap-2 nav-link group transition-colors hover:text-orange-500"
                       :class="currentPath === '/' ? 'text-orange-600 active' : 'text-gray-500'">
                        <i data-lucide="utensils" class="lucide-icon w-4 h-4 transition-colors" :class="currentPath === '/' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400'"></i>
                        RECETTES
                    </a>
                    <a href="/community" 
                       class="relative py-2 flex items-center gap-2 nav-link group transition-colors hover:text-orange-500"
                       :class="currentPath === '/community' ? 'text-orange-600 active' : 'text-gray-500'">
                        <i data-lucide="users" class="lucide-icon w-4 h-4 transition-colors" :class="currentPath === '/community' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400'"></i>
                        COMMUNAUTÉ
                    </a>
                    <a href="{{ route('login') }}" class="bg-orange-500 text-white px-8 py-3.5 rounded-full hover:bg-orange-600 hover:shadow-[0_10px_20px_-5px_rgba(249,115,22,0.4)] transition-all duration-300 scale-95 hover:scale-100 active:scale-90 uppercase text-[10px] tracking-widest font-black">
                        CONNEXION
                    </a>
                </div>

                <div class="md:hidden flex items-center gap-3">
                    <button @click="searchOpen = true" class="text-gray-500 p-2 hover:bg-orange-50 hover:text-orange-500 rounded-full transition-all">
                        <i data-lucide="search" class="w-6 h-6"></i>
                    </button>
                    <button @click="mobileMenuOpen = true" class="text-gray-900 bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm active:scale-90 transition-transform">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100]" x-cloak>
                <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-md"></div>
                <div x-show="mobileMenuOpen" 
                     x-transition:enter="transition transform duration-300 ease-out"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition transform duration-300 ease-in"
                     x-transition:leave-end="-translate-x-full"
                     class="fixed inset-y-0 left-0 w-[85%] max-w-sm bg-white shadow-2xl flex flex-col p-8">
                    
                    <div class="flex justify-between items-center mb-12">
                        <div class="text-2xl font-black text-gray-800 tracking-tighter italic border-b-2 border-orange-500 pb-1">OURA<span class="text-orange-500">TABLE</span></div>
                        <button @click="mobileMenuOpen = false" class="p-2 bg-gray-50 rounded-full text-gray-400 hover:rotate-90 transition-transform duration-300">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <div class="space-y-3 flex-grow">
                        <a href="/" class="group flex items-center gap-4 p-5 rounded-2xl transition-all"
                           :class="currentPath === '/' ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'bg-transparent text-gray-600 hover:bg-gray-50'">
                            <i data-lucide="utensils" class="w-6 h-6" :class="currentPath === '/' ? 'text-white' : 'text-orange-500'"></i>
                            <span class="font-black text-xs tracking-[0.2em]">RECETTES</span>
                        </a>
                        <a href="/community" class="group flex items-center gap-4 p-5 rounded-2xl transition-all"
                           :class="currentPath === '/community' ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'bg-transparent text-gray-600 hover:bg-gray-50'">
                            <i data-lucide="users" class="w-6 h-6" :class="currentPath === '/community' ? 'text-white' : 'text-orange-500'"></i>
                            <span class="font-black text-xs tracking-[0.2em]">COMMUNAUTÉ</span>
                        </a>
                    </div>

                    <div class="mt-auto border-t border-gray-200 pt-8">
                        <a href="/login" class="block w-full bg-orange-400 text-white text-center py-5 rounded-2xl font-black text-[11px] tracking-[0.3em] shadow-xl active:scale-95 transition-transform mb-8 hover:bg-orange-500">
                            SE CONNECTER
                        </a>
                        <div class="relative py-4 flex flex-col items-center">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                            <span class="relative bg-white px-4 text-[12px] font-black text-orange-600 uppercase tracking-[0.5em] italic">
                                L'art de bien manger
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </nav>

    <main class="pt-24 px-4 min-h-screen">
        @yield('content')
    </main>

    <script>
        document.addEventListener('alpine:init', () => { lucide.createIcons(); });
        window.addEventListener('resize', () => lucide.createIcons());
    </script>
</body>
</html>