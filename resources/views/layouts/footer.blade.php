{{-- Styles pour les animations et transitions avancées --}}
<style>
    /* Battement de cœur réaliste */
    @keyframes heart-beat {
        0%, 100% { transform: scale(1); filter: drop-shadow(0 0 0px rgba(239, 68, 68, 0)); }
        50% { transform: scale(1.25); filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.6)); }
    }
    .animate-heart-beat {
        animation: heart-beat 1.2s infinite cubic-bezier(0.4, 0, 0.6, 1);
    }
    
    /* Animation de la toque de chef */
    @keyframes chef-bounce {
        0%, 100% { transform: translateY(-50%) rotate(0deg); }
        50% { transform: translateY(-65%) rotate(10deg) scale(1.1); }
    }
    .animate-chef {
        animation: chef-bounce 2.5s infinite ease-in-out;
    }

    /* Transition douce pour les liens */
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
        background-color: #f97316; /* orange-500 */
        transition: width 0.3s ease;
    }
    .link-underline:hover::after {
        width: 100%;
    }

    /* Effet de brillance au survol de la card */
    .footer-card {
        transition: border-color 0.5s shadow 0.5s;
    }
    .footer-card:hover {
        border-color: rgba(249, 115, 22, 0.3);
        shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
</style>

<footer class="mt-24 pb-12 px-4 sm:px-6">
    <div class="footer-card max-w-7xl mx-auto bg-gray-900 rounded-[50px] md:rounded-[70px] p-10 md:p-16 relative overflow-hidden shadow-2xl border border-gray-800 group/card">
        
        {{-- Lueurs d'arrière-plan interactives --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-orange-500/5 rounded-full blur-[100px] group-hover/card:bg-orange-500/15 transition-colors duration-1000"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-orange-600/5 rounded-full blur-[100px] group-hover/card:bg-orange-600/15 transition-colors duration-1000"></div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 relative z-10">
            
            {{-- Branding avec effet Hover sur Logo --}}
            <div class="md:col-span-4 space-y-8 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 group/logo cursor-pointer">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/20 group-hover/logo:rotate-[372deg] transition-all duration-700">
                        <i data-lucide="utensils-crossed" class="w-7 h-7 text-white -rotate-12 group-hover/logo:rotate-0 transition-transform duration-700"></i>
                    </div>
                    <span class="text-3xl font-black text-white tracking-tighter italic">Oura<span class="text-orange-500 group-hover/logo:pl-1 transition-all">Table</span></span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mx-auto md:mx-0 font-medium opacity-80">
                    L'excellence dans votre boîte email. Chaque demande de recette est traitée avec la passion de nos chefs.
                </p>
                <div class="flex items-center justify-center md:justify-start gap-3">
                    @foreach(['instagram', 'facebook', 'youtube'] as $social)
                    <a href="#" class="w-11 h-11 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400 hover:bg-orange-500 hover:text-white hover:-translate-y-2 transition-all duration-300 border border-white/5 shadow-lg">
                        <i data-lucide="{{ $social }}" class="w-5 h-5"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Exploration avec Link Hover Effects --}}
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
                        {{-- Remplace 'login' par le nom de ta route qui mène à la page d'authentification --}}
                        <a href="{{ route('login') }}#{{ $anchor }}" class="text-white/90 hover:text-orange-500 text-sm font-bold transition-all flex items-center justify-center md:justify-start gap-3 group/auth">
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover/auth:bg-orange-500 group-hover/auth:scale-110 transition-all duration-300">
                                <i data-lucide="{{ $anchor === 'login' ? 'log-in' : 'user-plus' }}" class="w-4 h-4 text-orange-500 group-hover/auth:text-white transition-colors"></i>
                            </div>
                            <span class="group-hover/auth:translate-x-1 transition-transform">{{ $label }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Formulaire Interactif --}}
            <div class="md:col-span-5 bg-gradient-to-br from-white/5 to-transparent p-6 md:p-8 rounded-[40px] border border-white/5 backdrop-blur-md shadow-inner group/form">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                    <h4 class="text-white font-bold tracking-tighter text-xl italic group-hover/form:text-orange-500 transition-colors duration-500">Recette Coup de Cœur</h4>
                    <i data-lucide="heart" class="w-5 h-5 text-red-500 fill-red-500 animate-heart-beat"></i>
                </div>
                <p class="text-gray-500 text-xs mb-6 text-center md:text-left font-medium">Recevez les secrets de préparation directement par email.</p>
                
                <form action="#" method="POST" class="flex flex-col gap-4">
                    <div class="flex flex-col xl:flex-row gap-3">
                        <div class="relative flex-1 group/input">
                            <i data-lucide="chef-hat" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within/input:text-orange-500 transition-colors animate-chef"></i>
                            <input type="text" placeholder="Nom de la recette..." required
                                   class="w-full bg-gray-800/50 border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:bg-gray-800 transition-all duration-300 outline-none placeholder:text-gray-600 shadow-inner">
                        </div>
                        <div class="relative flex-1 group/input">
                            <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within/input:text-orange-500 transition-colors"></i>
                            <input type="email" placeholder="Votre email..." required
                                   class="w-full bg-gray-800/50 border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:bg-gray-800 transition-all duration-300 outline-none placeholder:text-gray-600 shadow-inner">
                        </div>
                    </div>
                    <button type="submit" class="group/btn relative overflow-hidden w-full bg-orange-500 py-4 rounded-2xl text-white font-bold text-sm transition-all duration-500 hover:shadow-[0_0_30px_rgba(249,115,22,0.4)] hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                        <div class="absolute inset-0 w-1/4 h-full bg-white/20 -skew-x-[45deg] -translate-x-[150%] group-hover/btn:translate-x-[400%] transition-transform duration-1000"></div>
                        <span class="relative z-10">Envoyer l'Inspiration</span>
                        <i data-lucide="send-horizontal" class="w-5 h-5 relative z-10 group-hover/btn:translate-x-2 transition-transform"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom avec micro-hover --}}
        <div class="mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.4em] hover:text-white transition-colors cursor-default">
                &copy; 2026 OuraTable — Signature d'Excellence
            </p>
            <div class="flex gap-8">
                <a href="#" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all hover:tracking-[0.2em]">Confidentialité</a>
                <a href="#" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all hover:tracking-[0.2em]">Mentions Légales</a>
            </div>
        </div>
    </div>
</footer>