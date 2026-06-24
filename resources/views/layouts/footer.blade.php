{{-- Styles pour les animations et transitions avancées --}}
<style>
    @keyframes heart-beat {
        0%, 100% { transform: scale(1); filter: drop-shadow(0 0 0px rgba(239, 68, 68, 0)); }
        50% { transform: scale(1.25); filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.6)); }
    }
    .animate-heart-beat {
        animation: heart-beat 1.2s infinite cubic-bezier(0.4, 0, 0.6, 1);
    }
   
    @keyframes chef-bounce {
        0%, 100% { transform: translateY(-50%) rotate(0deg); }
        50% { transform: translateY(-65%) rotate(10deg) scale(1.1); }
    }
    .animate-chef {
        animation: chef-bounce 2.5s infinite ease-in-out;
    }

    .link-underline {
        position: relative;
    }
    .link-underline::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: -2px;
        left: 0;
        background-color: #f97316;
        transition: width 0.3s ease;
    }
    .link-underline:hover::after {
        width: 100%;
    }

    .footer-card {
        transition: border-color 0.5s, box-shadow 0.5s;
    }
    .footer-card:hover {
        border-color: rgba(249, 115, 22, 0.3);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    /* Styles améliorés pour les icônes sociales */
    .social-icon {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .social-icon:hover {
        transform: translateY(-4px) scale(1.12) rotate(8deg);
        box-shadow: 0 10px 20px -5px rgb(249 115 22 / 0.3);
    }
    .social-icon svg {
        transition: all 0.4s ease;
    }
    .social-icon:hover svg {
        transform: scale(1.15);
    }
</style>

<footer class="mt-24 pb-12 px-4 sm:px-6">
    <div class="footer-card max-w-7xl mx-auto bg-gray-900 rounded-[50px] md:rounded-[70px] p-10 md:p-16 relative overflow-hidden shadow-2xl border border-gray-800 group/card">
       
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-orange-500/5 rounded-full blur-[100px] group-hover/card:bg-orange-500/15 transition-colors duration-1000"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-orange-600/5 rounded-full blur-[100px] group-hover/card:bg-orange-600/15 transition-colors duration-1000"></div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 relative z-10">
           
            {{-- Branding --}}
            <div class="md:col-span-4 space-y-8 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 group/logo cursor-pointer">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/20 group-hover/logo:rotate-[372deg] transition-all duration-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white -rotate-12 group-hover/logo:rotate-0 transition-transform duration-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75v3.75m-6 0h12M3 9.75v9a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18.75v-9M3 9.75h18M9 15.75h6" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white tracking-tighter italic">Oura<span class="text-orange-500 group-hover/logo:pl-1 transition-all">Table</span></span>
                </div>

                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mx-auto md:mx-0 font-medium opacity-80">
                    L'excellence dans votre boîte email. Chaque demande de recette est traitée avec la passion de nos chefs.
                </p>

                {{-- Icônes sociales plus stylées --}}
                <div class="flex items-center justify-center md:justify-start gap-4">
                    
                    <!-- Instagram -->
                    <a href="#" class="social-icon w-11 h-11 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400 hover:bg-gradient-to-br hover:from-pink-500 hover:to-orange-500 hover:text-white border border-white/10 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.649.069 4.849 0 3.2-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.2.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/>
                            <path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z"/>
                            <circle cx="18.406" cy="5.594" r="1.44"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="#" class="social-icon w-11 h-11 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#1877F2] hover:text-white border border-white/10 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>

                    <!-- YouTube -->
                    <a href="#" class="social-icon w-11 h-11 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#FF0000] hover:text-white border border-white/10 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.377.505 9.377.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.546 15.568V8.432L15.818 12l-6.272 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Le reste du footer reste identique -->
            <div class="md:col-span-3 text-center md:text-left">
                <h4 class="text-white font-black mb-6 tracking-[0.2em] uppercase text-[11px] opacity-30">Exploration</h4>
                <ul class="space-y-4 mb-8">
                    @foreach(['Nos Recettes', 'La communauté'] as $link)
                    <li>
                        <a href="#" class="link-underline text-gray-400 hover:text-orange-500 text-sm transition-colors inline-block font-medium">
                            {{ $link }}
                        </a>
                    </li>
                    @endforeach
                </ul>

                <h4 class="text-white font-black mb-6 tracking-[0.2em] uppercase text-[11px] opacity-30">Rejoindre la famille</h4>
                <ul class="space-y-4">
                    @foreach(['Se connecter' => 'login', "S'inscrire" => 'register'] as $label => $anchor)
                    <li>
                        <a href="{{ route('login') }}#{{ $anchor }}" class="text-white/90 hover:text-orange-500 text-sm font-bold transition-all flex items-center justify-center md:justify-start gap-3 group/auth">
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover/auth:bg-orange-500 group-hover/auth:scale-110 transition-all duration-300">
                                @if($anchor === 'login')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500 group-hover/auth:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4v12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500 group-hover/auth:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                @endif
                            </div>
                            <span class="group-hover/auth:translate-x-1 transition-transform">{{ $label }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Formulaire (inchangé) --}}
            <div class="md:col-span-5 bg-gradient-to-br from-white/5 to-transparent p-6 md:p-8 rounded-[40px] border border-white/5 backdrop-blur-md shadow-inner group/form">
                @include('layouts.partials.weekly-recipe')
            </div>
        </div>

        <div class="mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.4em] hover:text-white transition-colors cursor-default">
                &copy; 2026 OuraTable — Signature d'Excellence
            </p>
            <div class="flex gap-8">
                <a href="{{ route('page.confidentialite') }}" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all hover:tracking-[0.2em]">Confidentialité</a>
                <a href="{{ route('page.mentions') }}" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all hover:tracking-[0.2em]">Mentions Légales</a>
            </div>
        </div>
    </div>
</footer>