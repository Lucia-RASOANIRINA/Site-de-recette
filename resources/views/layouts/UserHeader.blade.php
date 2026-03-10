<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OURA TABLE | L'assiette ouverte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .group:hover .logo-box { transform: rotate(360deg); }
        
        /* Soulignement Desktop */
        .nav-link { position: relative; padding-bottom: 4px; transition: color 0.3s ease; }
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 2px;
            background: #f97316; transition: all 0.3s ease; transform: translateX(-50%);
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }

        /* Point Pulsant Mobile Uniquement */
        @keyframes pulse-mobile {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(249, 115, 22, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
        }
        .mobile-dot {
            width: 7px; height: 7px; background-color: #f97316; border-radius: 50%;
            display: inline-block; animation: pulse-mobile 2s infinite;
        }

        /* Rotation du chevron */
        .chevron-rotate { transform: rotate(180deg); }
        .chevron-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Menu Mobile */
        #mobile-menu {
            display: none;
            transform: translateY(-10px);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #mobile-menu.active { display: block; transform: translateY(0); }

        .stagger-item { opacity: 0; transform: translateX(-10px); transition: all 0.4s ease; }
        #mobile-menu.active .stagger-item { opacity: 1; transform: translateX(0); }

        .burger-line { height: 2px; background-color: #1f2937; border-radius: 10px; transition: all 0.3s ease; }
        .line-short { width: 16px; margin-left: auto; }

        #logout-modal { transition: opacity 0.3s ease, visibility 0.3s; }
        #modal-content { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }

        .mobile-link { transition: all 0.3s ease; border-radius: 18px; }
        .mobile-link:hover, .mobile-link.active { background-color: #fff7ed; color: #f97316; padding-left: 1.5rem; }
    </style>
</head>

<body class="bg-[#fcfaf8] font-sans">

<nav class="bg-white/90 backdrop-blur-xl fixed w-full z-50 border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <a href="/" class="flex items-center gap-4 group cursor-pointer no-underline">
                <div class="logo-box w-11 h-11 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30 transition-all duration-700">
                    <i data-lucide="utensils-crossed" class="w-6 h-6 text-white -rotate-12 group-hover:rotate-0 transition-transform"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-gray-900 tracking-tighter leading-none">OURA<span class="text-orange-500">TABLE</span></span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-1.5 ml-0.5">L'assiette ouverte</span>
                </div>
            </a>

            <div class="hidden md:flex flex-1 max-w-xs mx-10 group">
                <div class="relative w-full">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 group-focus-within:text-orange-500 transition-colors"></i>
                    <input type="text" placeholder="Rechercher..." class="w-full bg-gray-100/60 border border-transparent rounded-[20px] py-2.5 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all">
                </div>
            </div>

            <div class="hidden md:flex space-x-8 items-center font-bold text-[11px] tracking-widest text-gray-500">
                <a href="/" class="nav-link flex items-center gap-2 hover:text-orange-600 transition-colors uppercase {{ request()->is('/') ? 'active text-orange-600' : '' }}">
                    <i data-lucide="home" class="w-4 h-4 text-orange-500"></i> Accueil
                </a>
                <a href="/community" class="nav-link flex items-center gap-2 hover:text-orange-600 transition-colors uppercase {{ request()->is('community') ? 'active text-orange-600' : '' }}">
                    <i data-lucide="users" class="w-4 h-4 text-orange-500"></i> Communauté
                </a>
                <a href="/mes-recettes" class="nav-link flex items-center gap-2 hover:text-orange-600 transition-colors uppercase {{ request()->is('mes-recettes') ? 'active text-orange-600' : '' }}">
                    <i data-lucide="chef-hat" class="w-4 h-4 text-orange-500"></i> Mes Recettes
                </a>

                <div class="relative ml-4">
                    <button id="profile-btn" class="flex items-center group p-2 rounded-xl transition-all relative">
                        <div class="relative">
                            <i data-lucide="user-round" class="w-6 h-6 text-gray-700 group-hover:text-orange-500 transition-colors"></i>
                            <div class="absolute -top-1.5 -right-1 bg-white rounded-full">
                                <i data-lucide="chef-hat" class="w-3 h-3 text-orange-500"></i>
                            </div>
                        </div>
                        <i id="chevron-icon" data-lucide="chevron-down" class="w-3 h-3 ml-2 text-gray-400 group-hover:text-orange-500 chevron-transition"></i>
                    </button>
                    <div id="profile-menu" class="hidden absolute right-0 mt-4 w-52 bg-white rounded-[24px] shadow-2xl border border-gray-100 py-3 z-50 overflow-hidden">
                        <div class="px-5 py-2 border-b border-gray-50 mb-1">
                            <p class="text-[10px] text-orange-500 uppercase text-center tracking-widest font-black truncate">{{ Auth::user()->name ?? 'Mon Compte' }}</p>
                        </div>
                        <a href="/profile" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-all">
                            <i data-lucide="user" class="w-4 h-4"></i> Mon Profil
                        </a>
                        <a href="/mes-recettes" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-all">
                            <i data-lucide="heart" class="w-4 h-4"></i> Coups de cœur
                        </a>
                        <hr class="my-2 border-gray-50 mx-4">
                        <button onclick="openLogoutModal()" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-gray-600 hover:bg-red-50 hover:text-red-500 transition-all w-full text-left">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:hidden">
                <button id="mobile-btn" class="flex flex-col gap-1.5 p-3 focus:outline-none">
                    <div class="burger-line w-6"></div>
                    <div class="burger-line line-short"></div>
                    <div class="burger-line w-5"></div>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-50 px-6 py-8 space-y-6 shadow-2xl overflow-y-auto max-h-[90vh]">
        
        <div class="stagger-item relative w-full mb-2">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
            <input type="text" placeholder="Trouver une recette..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none transition-all">
        </div>

        <div class="stagger-item flex items-center gap-4 p-5 bg-orange-50/50 rounded-3xl border border-orange-100">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-orange-500 relative shrink-0">
                <i data-lucide="user-round" class="w-6 h-6"></i>
                <div class="absolute -top-1 -right-1 bg-white rounded-full p-0.5 shadow-sm border border-orange-100">
                    <i data-lucide="chef-hat" class="w-3 h-3"></i>
                </div>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-black text-gray-900 uppercase truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Compte Chef</p>
            </div>
        </div>

        <div class="flex flex-col gap-1">
            <a href="/" class="stagger-item mobile-link flex items-center justify-between px-5 py-4 text-sm font-bold text-gray-800 uppercase tracking-tight {{ request()->is('/') ? 'active' : '' }}">
                <span class="flex items-center gap-4"><i data-lucide="home" class="w-5 h-5 text-orange-500"></i> Accueil</span>
                <span class="mobile-dot"></span>
            </a>
            <a href="/community" class="stagger-item mobile-link flex items-center justify-between px-5 py-4 text-sm font-bold text-gray-800 uppercase tracking-tight {{ request()->is('community') ? 'active' : '' }}">
                <span class="flex items-center gap-4"><i data-lucide="users" class="w-5 h-5 text-orange-500"></i> Communauté</span>
                @if(request()->is('community')) <span class="mobile-dot"></span> @endif
            </a>
            <a href="/mes-recettes" class="stagger-item mobile-link flex items-center justify-between px-5 py-4 text-sm font-bold text-gray-800 uppercase tracking-tight {{ request()->is('mes-recettes') ? 'active' : '' }}">
                <span class="flex items-center gap-4"><i data-lucide="chef-hat" class="w-5 h-5 text-orange-500"></i> Mes Recettes</span>
                @if(request()->is('mes-recettes')) <span class="mobile-dot"></span> @endif
            </a>
            
            <hr class="stagger-item my-4 border-gray-50 mx-4">
            
            <a href="/profile" class="stagger-item mobile-link flex items-center gap-4 px-5 py-4 text-sm font-bold text-gray-800 uppercase tracking-tight">
                <i data-lucide="user" class="w-5 h-5 text-orange-500"></i> Mon Profil
            </a>

            <div class="stagger-item pt-4 text-center">
                <span class="footer-logo-underlined text-xs font-black italic tracking-tighter text-gray-900">OURA<span class="text-orange-500">TABLE</span></span>
            </div>

            <button onclick="openLogoutModal()" class="stagger-item mobile-link flex items-center gap-4 px-5 py-4 text-sm font-black text-red-500 uppercase tracking-widest mt-2">
                <i data-lucide="log-out" class="w-5 h-5"></i> Déconnexion
            </button>
        </div>
    </div>
</nav>

<div id="logout-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md opacity-0">
    <div id="modal-content" class="bg-white rounded-[40px] max-w-sm w-full p-8 shadow-2xl scale-90 transition-all border border-white">
        <div class="w-20 h-20 bg-orange-50 rounded-[30px] flex items-center justify-center mx-auto mb-6 rotate-3">
            <i data-lucide="cooking-pot" class="w-10 h-10 text-orange-500"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 text-center mb-2 tracking-tighter uppercase italic">Déjà <span class="text-orange-500">Rassasié ?</span></h3>
        <p class="text-gray-500 text-center text-sm mb-8 font-medium">Voulez-vous vraiment quitter la table ?</p>
        <div class="flex flex-col gap-3">
            <button onclick="closeLogoutModal()" class="w-full py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold text-[10px] uppercase">Non, je reste !</button>
            <a href="/" class="w-full py-4 bg-orange-500 text-white rounded-2xl font-bold text-[10px] uppercase text-center shadow-lg shadow-orange-500/30">Oui, déconnexion</a>
        </div>
    </div>
</div>

<main class="pt-24 px-4 min-h-screen">
    @yield('content')
</main>

<script>
    lucide.createIcons();

    // Menu Mobile
    const mobileBtn = document.getElementById("mobile-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    function toggleMenu() {
        const isOpen = mobileMenu.classList.toggle("active");
        mobileBtn.querySelector('.line-short').style.width = isOpen ? "24px" : "16px";
    }
    mobileBtn.addEventListener("click", toggleMenu);

    // Profil Dropdown
    const profileBtn = document.getElementById("profile-btn");
    const profileMenu = document.getElementById("profile-menu");
    const chevronIcon = document.getElementById("chevron-icon");

    profileBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const isHidden = profileMenu.classList.toggle("hidden");
        chevronIcon.classList.toggle("chevron-rotate", !isHidden);
    });

    document.addEventListener("click", () => {
        profileMenu.classList.add("hidden");
        chevronIcon.classList.remove("chevron-rotate");
    });

    // Modal
    const modal = document.getElementById("logout-modal");
    const modalContent = document.getElementById("modal-content");

    function openLogoutModal() {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        setTimeout(() => {
            modal.style.opacity = "1";
            modalContent.classList.replace("scale-90", "scale-100");
        }, 10);
    }

    function closeLogoutModal() {
        modal.style.opacity = "0";
        modalContent.classList.replace("scale-100", "scale-90");
        setTimeout(() => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }, 300);
    }
</script>
</body>
</html>