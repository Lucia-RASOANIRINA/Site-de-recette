{{-- Footer de l'espace administrateur — même charte (orange / sombre) --}}
<footer class="mt-16 pb-10 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto bg-gray-900 rounded-[32px] md:rounded-[44px] p-8 md:p-10 relative overflow-hidden shadow-2xl border border-gray-800">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-orange-500/10 rounded-full blur-[90px]"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            <div class="flex items-center gap-3 justify-center md:justify-start">
                <div class="w-11 h-11 bg-orange-500 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-2xl font-black text-white tracking-tighter italic">Oura<span class="text-orange-500">Table</span></span>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-orange-400 font-bold">Administration</p>
                </div>
            </div>

            <div class="text-center text-gray-400 text-sm flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
                <a href="/admin" class="hover:text-orange-500 transition-colors font-medium">Tableau de bord</a>
                <a href="/recette-de-la-semaine" class="hover:text-orange-500 transition-colors font-medium">Recette de la semaine</a>
                <a href="/UserCommunity" class="hover:text-orange-500 transition-colors font-medium">Communauté</a>
                <a href="/mes-recettes" class="hover:text-orange-500 transition-colors font-medium">Recettes</a>
                <a href="/UserHome" class="hover:text-orange-500 transition-colors font-medium">Voir le site</a>
            </div>

            <div class="text-center md:text-right text-gray-500 text-xs flex flex-col justify-center">
                <span>Connecté : <span class="text-gray-300 font-semibold">{{ auth()->user()->name ?? '' }}</span></span>
                <span class="text-[10px] uppercase tracking-[0.3em] mt-1">&copy; {{ date('Y') }} OuraTable</span>
            </div>
        </div>
    </div>
</footer>
