{{-- Widget "Recette Coup de Cœur de la semaine" — vote public par nom de recette + email --}}
<div class="flex items-center justify-center md:justify-start gap-3 mb-2">
    <h4 class="text-white font-bold tracking-tighter text-xl italic group-hover/form:text-orange-500 transition-colors duration-500">Recette Coup de Cœur</h4>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 fill-red-500 animate-heart-beat" viewBox="0 0 24 24">
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3 9.24 3 10.91 3.81 12 5.08 13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
</div>
<p class="text-gray-500 text-xs mb-5 text-center md:text-left font-medium">
    Votez pour votre recette préférée de la semaine — aucun compte nécessaire.
    @if($weeklyTop)
        Actuellement en tête : <span class="text-orange-400 font-bold">{{ $weeklyTop->titre }}</span>.
    @endif
</p>

<form action="{{ route('recette.vote') }}" method="POST" class="flex flex-col gap-4">
    @csrf
    <div class="flex flex-col xl:flex-row gap-3">
        <div class="relative flex-1 group/input">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within/input:text-orange-500 transition-colors animate-chef" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
            </svg>
            <input type="text" name="recette_nom" placeholder="Nom de la recette..." required
                value="{{ old('recette_nom') }}" list="weekly-recipe-list"
                class="w-full bg-gray-800/50 border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:bg-gray-800 transition-all duration-300 outline-none placeholder:text-gray-600 shadow-inner">
        </div>
        <div class="relative flex-1 group/input">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 group-focus-within/input:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2.01 2.01 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2" />
            </svg>
            <input type="email" name="email" placeholder="Votre email..." required value="{{ old('email') }}"
                class="w-full bg-gray-800/50 border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:bg-gray-800 transition-all duration-300 outline-none placeholder:text-gray-600 shadow-inner">
        </div>
    </div>

    <button type="submit" class="group/btn relative overflow-hidden w-full bg-orange-500 py-4 rounded-2xl text-white font-bold text-sm transition-all duration-500 hover:shadow-[0_0_30px_rgba(249,115,22,0.4)] hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
        <div class="absolute inset-0 w-1/4 h-full bg-white/20 -skew-x-[45deg] -translate-x-[150%] group-hover/btn:translate-x-[400%] transition-transform duration-1000"></div>
        <span class="relative z-10">Envoyer mon coup de cœur</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative z-10 group-hover/btn:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.874L5.999 12zm0 0h7.07" />
        </svg>
    </button>
</form>

<datalist id="weekly-recipe-list">
    @foreach(\App\Models\Recette::orderBy('titre')->pluck('titre') as $t)
        <option value="{{ $t }}"></option>
    @endforeach
</datalist>

<a href="{{ url('/recette-de-la-semaine') }}" class="block text-center md:text-left text-orange-400 hover:text-orange-300 text-xs font-bold mt-4 underline-offset-4 hover:underline">
    Voir le classement de la semaine →
</a>
