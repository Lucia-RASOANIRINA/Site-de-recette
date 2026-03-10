{{-- appels du header --}}
@extends('layouts.header')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 10s linear infinite;
    }
</style>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
     x-data="{ 
            currentIndex: 0, 
            showLoginModal: false,
            total: {{ $recettes->count() }},
            next() { this.currentIndex = (this.currentIndex + 1) % this.total },
            prev() { this.currentIndex = (this.currentIndex - 1 + this.total) % this.total }
         }">
    
    {{-- SECTION HAUTE (Encapsulée pour éviter les bugs de superposition au scroll) --}}
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 mb-20">
        <div class="w-full md:w-1/2 text-center md:text-left flex flex-col justify-center">
    
            <div class="flex items-center justify-center md:justify-start gap-6 mb-8 group">
                <div class="relative shrink-0">
                    <div class="absolute -inset-2 bg-orange-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative flex items-center justify-center w-16 h-16 md:w-20 md:h-20 border-2 border-orange-500 rounded-full shadow-2xl bg-white transition-transform duration-500 group-hover:rotate-12">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-orange-500 rounded-full flex items-center justify-center shadow-inner">
                            <i data-lucide="chef-hat" class="w-7 h-7 md:w-9 md:h-9 text-white"></i>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-gray-900 tracking-tighter flex-1">
                    <div class="text-4xl md:text-6xl font-black tracking-tighter text-orange-500 italic leading-none flex items-center justify-center md:justify-start whitespace-nowrap">
                        <span>L'Univers</span>
                        <span class="relative ml-3 text-gray-800 inline-block group">
                            Culinaire
                            {{-- La ligne qui s'anime de gauche à droite --}}
                            <span class="absolute -bottom-2 left-0 h-2 bg-orange-500/20 transition-all duration-1000 ease-out w-0"
                                x-init="setTimeout(() => $el.style.width = '100%', 500)"></span>
                        </span>
                    </div>
                </h2>
            </div>

            <div class="relative p-6 md:p-8 bg-white rounded-3xl shadow-lg border border-gray-200 max-w-xl mx-auto md:mx-0 group hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                <div class="absolute -top-3 -left-3 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center shadow-md">
                    <i data-lucide="quote" class="w-6 h-6 text-orange-500 rotate-180"></i>
                </div>

                <p class="text-gray-600 font-medium text-base md:text-lg leading-relaxed text-center md:text-left pt-4">
                    Bienvenue dans notre galerie gourmande 
                    <i data-lucide="utensils" class="inline-block w-5 h-5 text-orange-400 mb-1 mx-1 animate-pulse"></i>. 
                    Parcourez nos créations et laissez-vous inspirer par des saveurs authentiques 
                    <i data-lucide="heart" class="inline-block w-5 h-5 text-red-400 mb-1 mx-1 hover:scale-125 transition-transform"></i>, 
                    pensées pour sublimer chacun de vos instants à table 
                    <i data-lucide="sparkles" class="inline-block w-5 h-5 text-yellow-400 mb-1 mx-1 animate-spin-slow"></i>.
                </p>

                <div class="mt-6 pt-4 border-t border-gray-200 text-center md:text-right">
                    <span class="text-xs font-black uppercase tracking-[0.3em] text-gray-300">
                        Oura<span class="text-orange-500/50">Table</span>
                    </span>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-center md:justify-start gap-4 group">
                <div class="h-1 w-12 bg-orange-500/30 rounded-full group-hover:w-16 transition-all duration-500"></div>
                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-orange-600 italic">
                    L'art de bien manger
                </span>
            </div>
        </div>

        <div class="w-full md:w-1/2 relative flex justify-center items-center h-[450px]">
            {{-- Boutons avec z-index réduit (z-40) pour ne pas gêner le header --}}
            <button @click="prev()" 
                    class="absolute left-4 md:-left-4 z-40 p-3 bg-white/90 hover:bg-orange-500 text-gray-800 hover:text-white rounded-full shadow-2xl transition-all duration-300 active:scale-90 border border-gray-200 group">
                <i data-lucide="chevron-left" class="w-6 h-6 group-hover:-translate-x-1 transition-transform"></i>
            </button>

            <button @click="next()" 
                    class="absolute right-4 md:-right-4 z-40 p-3 bg-white/90 hover:bg-orange-500 text-gray-800 hover:text-white rounded-full shadow-2xl transition-all duration-300 active:scale-90 border border-gray-200 group">
                <i data-lucide="chevron-right" class="w-6 h-6 group-hover:translate-x-1 transition-transform"></i>
            </button>

           @if($recettes->count() > 0)
                @foreach($recettes as $index => $recette)
                    <div class="absolute transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)]"
                        :style="'z-index: ' + (currentIndex === {{ $index }} ? 30 : (10 - Math.abs(currentIndex - {{ $index }})))"
                        :class="{
                            'scale-110 rotate-0 opacity-100 translate-x-0': currentIndex === {{ $index }},
                            'scale-90 -rotate-12 -translate-x-32 md:-translate-x-40 opacity-40 blur-[2px]': {{ $index }} < currentIndex,
                            'scale-90 rotate-12 translate-x-32 md:translate-x-40 opacity-40 blur-[2px]': {{ $index }} > currentIndex,
                            'opacity-70 blur-[1px] translate-x-16 md:translate-x-20': ({{ $index }} === currentIndex + 1) || ({{ $index }} === currentIndex - 1)
                        }">
                        
                        <div class="relative group">
                            {{-- L'icône dynamique : elle ne s'affiche que si l'index est l'index actif --}}
                            <div x-show="currentIndex === {{ $index }}"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-50"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute -top-4 -left-4 bg-gray-900 text-white p-3 rounded-2xl shadow-xl z-[60]">
                                <i data-lucide="award" class="w-5 h-5 text-orange-400"></i>
                            </div>

                            <img src="{{ asset('storage/' . $recette->image_path) }}" 
                                class="w-48 h-64 md:w-64 md:h-80 object-cover rounded-[2.5rem] shadow-2xl border-[10px] border-white transition-all duration-300 group-hover:scale-[1.03] group-hover:border-orange-400">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- GRILLE DES RECETTES (Distance augmentée avec mt-24 et bordure visible) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mt-24 pt-16 border-t border-gray-200">
        @foreach($recettes as $recette)
            @php
                $likesCount = $recette->likes_count ?? 0;
                $rating = min(5, ($likesCount * 0.1) + 3.5);
            @endphp
            <div class="group relative bg-white rounded-[40px] shadow-sm border border-gray-200 p-4 pt-0 transition-all duration-500 hover:shadow-xl hover:-translate-y-2">
                <div class="relative -mt-12 mb-6 flex justify-center">
                    <div class="relative w-48 h-48 rounded-full transition-transform duration-700 ease-out group-hover:scale-110 group-hover:rotate-6">
                        <div class="absolute inset-2 rounded-full bg-black/5 blur-xl group-hover:bg-black/20 transition-all duration-700"></div>
                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                            class="relative w-full h-full object-cover rounded-full border-[8px] border-white shadow-sm">
                    </div>
                    <div class="absolute top-8 right-0 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1 border border-gray-100">
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
                                <li class="text-xs text-gray-600 flex justify-between border-b border-gray-100 pb-1">
                                    <span>• {{ $ingredient->nom }}</span>
                                    <span class="font-bold text-gray-500">{{ $ingredient->quantite }}</span>
                                </li>
                            @endforeach
                            <div x-show="open" x-collapse x-cloak>
                                @foreach($recette->ingredients->skip(2) as $ingredient)
                                    <li class="text-xs text-gray-600 flex justify-between border-b border-gray-100 py-1">
                                        <span>• {{ $ingredient->nom }}</span>
                                        <span class="font-bold text-gray-400">{{ $ingredient->quantite }}</span>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                        @if($recette->ingredients->count() > 2)
                            <button @click="open = !open" class="mt-3 text-[10px] text-orange-500 font-black uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                                <span x-text="open ? 'Réduire' : 'Détails complets'"></span>
                                <i data-lucide="chevron-right" class="w-3 h-3" :class="open ? 'rotate-90' : ''"></i>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            {{ $recette->ingredients->count() }} Ingrédients
                        </span>
                        
                        <button type="button" @click="showLoginModal = true" 
                                class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-50 hover:bg-orange-50 transition-all border border-gray-100 group/btn">
                            <span class="text-[10px] font-bold text-gray-500 group-hover/btn:text-red-500 uppercase tracking-widest">J'adore</span>
                            <i data-lucide="heart" class="w-4 h-4 text-gray-300 group-hover/btn:text-red-500"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- MODALE LOGIN --}}
    <div x-show="showLoginModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
         x-cloak>
    
        <div @click.away="showLoginModal = false" 
             class="bg-white/95 backdrop-blur-xl w-full max-w-sm rounded-[45px] p-10 shadow-2xl text-center border border-white/20 relative overflow-hidden">
            
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-50 rounded-full blur-3xl opacity-50"></div>

            <div class="relative w-20 h-20 bg-orange-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i data-lucide="heart" class="w-10 h-10 fill-red-500 animate-pulse"></i>
            </div>

            <h3 class="text-3xl font-black text-gray-900 mb-3 tracking-tighter uppercase italic">
                Coup de <span class="text-orange-500">foudre !</span>
            </h3>
            
            <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium">
                Vous avez craqué pour cette recette ? Rejoignez la <span class="text-gray-800 font-bold italic text-base">Table Oura</span> pour enregistrer toutes vos pépites culinaires.
            </p>

            <div class="flex flex-col gap-4">
                <a href="{{ route('login') }}" 
                   class="w-full py-4 bg-orange-500 text-white rounded-[20px] font-black shadow-lg shadow-orange-500/30 text-center uppercase tracking-widest text-xs transition-all hover:bg-orange-600 active:scale-95">
                    Se connecter
                </a>
                
                <button @click="showLoginModal = false" 
                        class="w-full py-2 text-gray-400 font-bold text-[11px] uppercase tracking-[0.2em] hover:text-gray-600 transition-colors">
                    Peut-être plus tard
                </button>
            </div>
        </div>
    </div>
</div>

{{-- APPEL DU FOOTER --}}
@include('layouts.footer')
@endsection