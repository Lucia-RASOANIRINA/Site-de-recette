@extends('layouts.UserHeader')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 pt-10">
        @foreach($recettes as $recette)
            @php
                // 1. Gestion des Likes et de l'état "J'adore"
                $isLiked = auth()->check() && $recette->likes->contains('user_id', auth()->id());
                $likesCount = $recette->likes->count();

                // 2. Calcul du Rating (évite l'erreur undefined)
                // On part d'une base de 3.5, et on ajoute 0.1 par like (max 5)
                $rating = min(5, ($likesCount * 0.1) + 3.5);
            @endphp

            <div class="group relative bg-white rounded-[40px] shadow-sm border border-gray-50 flex overflow-hidden transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
                
                <div class="relative w-2/5 min-w-[160px] flex items-center justify-center p-4">
                    <div class="absolute inset-y-0 left-0 w-3/4 bg-orange-50/60 rounded-r-[40px] -z-10 transition-colors group-hover:bg-orange-100/50"></div>
                    
                    <div class="relative w-32 h-32 md:w-40 md:h-40 rounded-full">
                        <div class="absolute inset-2 rounded-full bg-black/5 blur-xl group-hover:bg-black/15 transition-all duration-700"></div>
                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                             class="relative w-full h-full object-cover rounded-full border-[6px] border-white shadow-md transition-transform duration-700 group-hover:scale-105 group-hover:rotate-3">
                        
                        <div class="absolute -top-2 -right-2 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1">
                            <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                            <span class="text-[10px] font-black">{{ number_format($rating, 1) }}</span>
                        </div>
                    </div>
                </div>

                <div class="w-3/5 p-6 pl-2 flex flex-col justify-center">
                    <div class="mb-1">
                        <h3 class="font-bold text-lg text-gray-800 truncate">{{ $recette->titre }}</h3>
                        <p class="text-gray-400 text-[11px] line-clamp-2 italic">"{{ $recette->description }}"</p>
                    </div>

                    <div x-data="{ open: false }" class="mb-4">
                        <ul class="space-y-1.5 mt-2">
                            @foreach($recette->ingredients->take(2) as $ingredient)
                                <li class="text-[11px] text-gray-600 flex justify-between border-b border-gray-50 pb-1">
                                    <span>• {{ $ingredient->nom }}</span>
                                    <span class="font-bold text-gray-400">{{ $ingredient->quantite }}</span>
                                </li>
                            @endforeach
                            
                            <div x-show="open" x-collapse>
                                @foreach($recette->ingredients->skip(2) as $ingredient)
                                    <li class="text-[11px] text-gray-600 flex justify-between border-b border-gray-50 py-1">
                                        <span>• {{ $ingredient->nom }}</span>
                                        <span class="font-bold text-gray-400">{{ $ingredient->quantite }}</span>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                        
                        @if($recette->ingredients->count() > 2)
                            <button @click="open = !open" class="mt-2 text-[9px] text-orange-500 font-black uppercase tracking-widest flex items-center gap-1 hover:text-orange-600">
                                <span x-text="open ? 'Voir moins' : '+ ' + ({{ $recette->ingredients->count() }} - 2) + ' ingrédients'"></span>
                                <i data-lucide="chevron-down" class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                        <div class="flex flex-col">
                            <span class="text-[8px] text-gray-400 uppercase tracking-tighter">Partagé par</span>
                            <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $recette->user->name ?? 'Chef Oura' }}</span>
                        </div>
                        
                        @auth
                            <button onclick="likeRecette({{ $recette->id }}, this)" 
                                    class="like-btn flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 {{ $isLiked ? 'bg-red-50' : 'bg-gray-50' }} group/btn">
                                <span class="text-[10px] font-black {{ $isLiked ? 'text-red-500' : 'text-gray-500' }} uppercase">J'adore</span>
                                <i data-lucide="heart" 
                                   class="w-4 h-4 transition-all duration-300 {{ $isLiked ? 'text-red-500 fill-red-500 scale-110' : 'text-gray-300' }}"></i>
                            </button>
                        @endauth
                        
                        @guest
                            <button onclick="openLoginModal()" class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-50 hover:bg-orange-50 transition-colors">
                                <span class="text-[10px] font-bold text-gray-500 uppercase">J'adore</span>
                                <i data-lucide="heart" class="w-4 h-4 text-gray-300"></i>
                            </button>
                        @endguest
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function likeRecette(id, btn) {
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        const isCurrentlyLiked = icon.classList.contains('fill-red-500');

        // Changement visuel immédiat
        if (isCurrentlyLiked) {
            icon.classList.remove('text-red-500', 'fill-red-500', 'scale-110');
            icon.classList.add('text-gray-300');
            span.classList.remove('text-red-500');
            span.classList.add('text-gray-500');
            btn.classList.replace('bg-red-50', 'bg-gray-50');
        } else {
            icon.classList.add('text-red-500', 'fill-red-500', 'scale-110');
            icon.classList.remove('text-gray-300');
            span.classList.add('text-red-500');
            span.classList.remove('text-gray-500');
            btn.classList.replace('bg-gray-50', 'bg-red-50');
        }

        // Appel AJAX
        fetch(`/recettes/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => { console.log(data.message); })
        .catch(error => { console.error('Erreur:', error); });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) { lucide.createIcons(); }
    });
</script>

<style>
    .like-btn i.scale-110 { animation: heartBeat 0.3s ease-out; }
    @keyframes heartBeat {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1.1); }
    }
</style>
@endsection