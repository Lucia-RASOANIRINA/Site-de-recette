{{-- appels du header --}}
@extends('layouts.header')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
     x-data="{ 
            currentIndex: 0, 
            showLoginModal: false,
            total: {{ $recettes->count() }},
            next() { this.currentIndex = (this.currentIndex + 1) % this.total },
            prev() { this.currentIndex = (this.currentIndex - 1 + this.total) % this.total }
         }">
    
    <div class="flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="w-full md:w-1/2 text-center md:text-left">
            <h2 class="text-gray-900 tracking-tighter">
                <div class="text-4xl md:text-5xl font-black tracking-tighter text-orange-500 italic leading-none">
                    Notre <span class="text-gray-800 block md:inline">Top sélection</span>
                </div>
            </h2>
            <p class="text-gray-400 font-medium text-sm mt-6 tracking-[0.1em] max-w-md mx-auto md:mx-0">
                L'excellence de la <span class="text-gray-800 font-bold">OuraTable</span>. 
                Explorez nos <span x-text="total"></span> créations via les commandes tactiles.
            </p>
            <div class="mt-8 flex items-center justify-center md:justify-start gap-2">
                <span class="h-[1px] w-8 bg-orange-500"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-500 italic">Incontournable</span>
            </div>
        </div>

        <div class="w-full md:w-1/2 relative flex justify-center items-center h-[450px]">
            {{-- Bouton Précédent --}}
            <button @click="prev()" 
                    class="absolute left-4 md:-left-4 z-[70] p-3 bg-white/90 hover:bg-orange-500 text-gray-800 hover:text-white rounded-full shadow-2xl transition-all duration-300 active:scale-90 border border-gray-100 group">
                <i data-lucide="chevron-left" class="w-6 h-6 group-hover:-translate-x-1 transition-transform"></i>
            </button>

            {{-- Bouton Suivant --}}
            <button @click="next()" 
                    class="absolute right-4 md:-right-4 z-[70] p-3 bg-white/90 hover:bg-orange-500 text-gray-800 hover:text-white rounded-full shadow-2xl transition-all duration-300 active:scale-90 border border-gray-100 group">
                <i data-lucide="chevron-right" class="w-6 h-6 group-hover:translate-x-1 transition-transform"></i>
            </button>

            @if($recettes->count() > 0)
                @foreach($recettes as $index => $recette)
                    <div class="absolute transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)]"
                        :style="'z-index: ' + (currentIndex === {{ $index }} ? 50 : (20 - Math.abs(currentIndex - {{ $index }})))"
                        :class="{
                            {{-- IMAGE CENTRALE ACTIVE --}}
                            'scale-110 rotate-0 opacity-100 translate-x-0': currentIndex === {{ $index }},
                            
                            {{-- IMAGES À GAUCHE (Déjà passées) --}}
                            'scale-90 -rotate-12 -translate-x-32 md:-translate-x-40 opacity-40 blur-[2px]': {{ $index }} < currentIndex,
                            
                            {{-- IMAGES À DROITE (À venir) --}}
                            'scale-90 rotate-12 translate-x-32 md:translate-x-40 opacity-40 blur-[2px]': {{ $index }} > currentIndex,

                            {{-- EFFET D'EMPILEMENT (Proche de l'active) --}}
                            'opacity-70 blur-[1px] translate-x-16 md:translate-x-20': ({{ $index }} === currentIndex + 1) || ({{ $index }} === currentIndex - 1)
                        }">
                        
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $recette->image_path) }}" 
                                class="w-48 h-64 md:w-64 md:h-80 object-cover rounded-[2.5rem] shadow-2xl border-[10px] border-white transition-all duration-300 group-hover:scale-[1.03] group-hover:border-orange-400">
                            
                            @if($loop->first)
                                <div class="absolute -top-4 -left-4 bg-gray-900 text-white p-3 rounded-2xl shadow-xl z-[60]">
                                    <i data-lucide="award" class="w-5 h-5 text-orange-400"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pt-10">
        @foreach($recettes as $recette)
            @php
                $likesCount = $recette->likes_count ?? 0;
                $rating = min(5, ($likesCount * 0.1) + 3.5);
            @endphp
            <div class="group relative bg-white rounded-[40px] shadow-sm border border-gray-50 p-4 pt-0 transition-all duration-500 hover:shadow-xl hover:-translate-y-2">
                <div class="relative -mt-12 mb-6 flex justify-center">
                    <div class="relative w-48 h-48 rounded-full transition-transform duration-700 ease-out group-hover:scale-110 group-hover:rotate-6">
                        <div class="absolute inset-2 rounded-full bg-black/5 blur-xl group-hover:bg-black/20 transition-all duration-700"></div>
                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                            class="relative w-full h-full object-cover rounded-full border-[8px] border-white shadow-sm">
                    </div>
                    <div class="absolute top-8 right-0 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1">
                        <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                        <span class="text-[10px] font-black">{{ number_format($rating, 1) }}</span>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h3 class="font-bold text-xl text-gray-800 mb-2 truncate">{{ $recette->titre }}</h3>
                    <p class="text-gray-500 text-xs mb-4 line-clamp-2 italic">"{{ $recette->description }}"</p>

                    <div x-data="{ open: false }" class="mb-6">
                        <ul class="space-y-2">
                            @foreach($recette->ingredients->take(2) as $ingredient)
                                <li class="text-xs text-gray-600 flex justify-between border-b border-gray-50 pb-1">
                                    <span>• {{ $ingredient->nom }}</span>
                                    <span class="font-bold text-gray-500">{{ $ingredient->quantite }}</span>
                                </li>
                            @endforeach
                            <div x-show="open" x-collapse x-cloak>
                                @foreach($recette->ingredients->skip(2) as $ingredient)
                                    <li class="text-xs text-gray-600 flex justify-between border-b border-gray-50 py-1">
                                        <span>• {{ $ingredient->nom }}</span>
                                        <span class="font-bold text-gray-400">{{ $ingredient->quantite }}</span>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                        @if($recette->ingredients->count() > 2)
                            <button @click="open = !open" class="mt-3 text-[10px] text-orange-500 font-black uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                                <span x-text="open ? 'Réduire' : 'Voir tous les détails'"></span>
                                <i data-lucide="chevron-right" class="w-3 h-3" :class="open ? 'rotate-90' : ''"></i>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-50 pt-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            {{ $recette->ingredients->count() }} Ingrédients
                        </span>
                        
                        {{-- Modification ici pour le bouton Like --}}
                        @auth
                            <button onclick="likeRecette({{ $recette->id }}, this)" 
                                    class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-50 hover:bg-red-50 transition-all group/btn">
                                <span class="text-[10px] font-bold text-gray-500 group-hover/btn:text-red-500 uppercase">J'adore</span>
                                <i data-lucide="heart" class="w-4 h-4 text-gray-300 group-hover/btn:text-red-500 transition-colors"></i>
                            </button>
                        @endauth
                        @guest
                            <button @click="showLoginModal = true" 
                                    class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-50 hover:bg-orange-50 transition-all group/btn">
                                <span class="text-[10px] font-bold text-gray-500 group-hover/btn:text-red-500 uppercase">J'adore</span>
                                <i data-lucide="heart" class="w-4 h-4 text-gray-300 group-hover/btn:text-red-500"></i>
                            </button>
                        @endguest
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Bloc Message Box ajouté à la fin, toujours dans le x-data --}}
    <div x-show="showLoginModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
         x-cloak>
        <div @click.away="showLoginModal = false" 
             class="bg-white w-full max-w-sm rounded-[40px] p-8 shadow-2xl text-center transform transition-all">
            <div class="w-16 h-16 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="user-circle" class="w-8 h-8"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-800 mb-2 tracking-tighter">Presque là !</h3>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                Vous devez être connecté à la <span class="text-orange-500 font-bold">Table Oura</span> pour enregistrer vos recettes favorites.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}" class="w-full py-4 bg-orange-500 text-white rounded-2xl font-bold shadow-lg shadow-orange-500/30 text-center">
                    Se connecter
                </a>
                <button @click="showLoginModal = false" class="w-full py-3 text-gray-400 font-bold text-sm">Plus tard</button>
            </div>
        </div>
    </div>
    {{-- APPEL DU FOOTER --}}
    @include('layouts.footer')
</div>
@endsection