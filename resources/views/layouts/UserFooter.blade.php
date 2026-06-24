{{-- Footer pour les utilisateurs connectés — même charte (orange / sombre) --}}
<style>
    @keyframes heart-beat {
        0%, 100% { transform: scale(1); filter: drop-shadow(0 0 0px rgba(239,68,68,0)); }
        50% { transform: scale(1.25); filter: drop-shadow(0 0 8px rgba(239,68,68,.6)); }
    }
    .animate-heart-beat { animation: heart-beat 1.2s infinite cubic-bezier(.4,0,.6,1); }
    .uf-link::after { content:''; position:absolute; width:0; height:1px; bottom:-2px; left:0; background:#f97316; transition:width .3s ease; }
    .uf-link:hover::after { width:100%; }
</style>

<footer class="mt-24 pb-12 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto bg-gray-900 rounded-[40px] md:rounded-[60px] p-10 md:p-14 relative overflow-hidden shadow-2xl border border-gray-800">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-orange-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-orange-600/10 rounded-full blur-[100px]"></div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 relative z-10">
            {{-- Branding + navigation utilisateur --}}
            <div class="md:col-span-4 space-y-6 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75v3.75m-6 0h12M3 9.75v9a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18.75v-9M3 9.75h18M9 15.75h6" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white tracking-tighter italic">Oura<span class="text-orange-500">Table</span></span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mx-auto md:mx-0 opacity-80">
                    Bienvenue {{ auth()->user()->name ?? '' }} ! Partagez vos recettes, vos coups de cœur et échangez avec la communauté.
                </p>
            </div>

            {{-- Liens rapides --}}
            <div class="md:col-span-3 text-center md:text-left">
                <h4 class="text-white font-black mb-6 tracking-[0.2em] uppercase text-[11px] opacity-30">Mon espace</h4>
                <ul class="space-y-4">
                    @foreach(['Accueil' => '/UserHome', 'Communauté' => '/UserCommunity', 'Mes recettes' => '/mes-recettes', 'Mon profil' => '/profile', 'Coups de cœur' => '/profile#coup-de-coeur'] as $label => $href)
                    <li>
                        <a href="{{ $href }}" class="uf-link relative text-gray-400 hover:text-orange-500 text-sm transition-colors inline-block font-medium">{{ $label }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Widget recette de la semaine --}}
            <div class="md:col-span-5 bg-gradient-to-br from-white/5 to-transparent p-6 md:p-8 rounded-[40px] border border-white/5 backdrop-blur-md shadow-inner">
                @include('layouts.partials.weekly-recipe')
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.4em]">&copy; {{ date('Y') }} OuraTable — Signature d'Excellence</p>
            <div class="flex gap-8">
                <a href="{{ route('page.confidentialite') }}" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all">Confidentialité</a>
                <a href="{{ route('page.mentions') }}" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all">Mentions Légales</a>
            </div>
        </div>
    </div>
</footer>
