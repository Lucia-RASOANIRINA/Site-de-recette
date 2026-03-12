@extends('layouts.UserHeader')

@section('content')

<!-- AlpineJS pour gérer afficher/cacher -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 pt-10">

        @foreach($recettes as $recette)
            @php
                $isLiked = auth()->check() && $recette->likes->contains('user_id', auth()->id());
                $likesCount = $recette->likes->count();
                $rating = min(5, ($likesCount * 0.1) + 3.5);
                $totalIngredients = $recette->ingredients->count();
            @endphp

            <div class="relative bg-white rounded-[40px] shadow-sm border border-gray-100 flex overflow-hidden transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
                
                {{-- IMAGE --}}
                <div class="relative w-2/5 min-w-[160px] flex items-center justify-center p-4 group">

                    <!-- Fond orange permanent -->
                    <div class="absolute inset-y-0 left-0 w-3/4 bg-orange-500 rounded-r-[40px] transition-colors duration-500 group-hover:bg-orange-600"></div>

                    <div class="relative z-10 w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden transition-transform duration-500 group-hover:scale-105 group-hover:rotate-2">

                        <!-- Glow derrière l'image -->
                        <div class="absolute inset-2 rounded-full bg-orange-900/10 blur-xl transition-all duration-500 group-hover:bg-orange-900/20"></div>

                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                            class="relative w-full h-full object-cover rounded-full border-[6px] border-white shadow-lg transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">

                        <!-- rating -->
                        <div class="absolute -top-2 -right-2 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1 border border-orange-50">
                            <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                            <span class="text-[10px] font-black text-gray-800">{{ number_format($rating,1) }}</span>
                        </div>

                    </div>
                </div>

                {{-- CONTENU --}}
                <div class="w-3/5 p-6 pl-2 flex flex-col justify-center">

                    {{-- TITRE --}}
                    <div class="mb-3">
                        <h3 class="font-bold text-lg text-center text-gray-900 truncate">{{ $recette->titre }}</h3>
                        <p class="text-gray-500 text-[11px] italic line-clamp-1">
                            "{{ $recette->description }}"
                        </p>
                    </div>

                    {{-- INGREDIENTS --}}
                    <div x-data="{ open:false }" class="mb-4 bg-gray-50 p-4 rounded-[24px] border border-gray-100">

                        <div class="flex items-center justify-between mb-3">

                            <div class="flex items-center gap-2">
                                <i data-lucide="utensils" class="w-3.5 h-3.5 text-orange-500"></i>
                                <span class="text-[10px] font-black text-gray-500 uppercase">
                                    {{ $totalIngredients }} ingrédients
                                </span>
                            </div>

                            {{-- bouton toggle --}}
                            @if($totalIngredients > 4)
                            <button @click="open = !open"
                                class="p-1 rounded-full hover:bg-orange-100 transition">

                                <i data-lucide="list-ordered"
                                   class="w-4 h-4 text-orange-500 transition-transform"
                                   :class="open ? 'rotate-180' : ''">
                                </i>

                            </button>
                            @endif

                        </div>

                        <ul class="space-y-2">

                            {{-- 4 premiers --}}
                            @foreach($recette->ingredients->take(4) as $ingredient)
                            <li class="text-[11px] text-gray-700 flex justify-between border-b border-gray-200 pb-1.5">
                                <span class="font-medium">• {{ $ingredient->nom }}</span>
                                <span class="font-black text-orange-600/70">{{ $ingredient->quantite }}</span>
                            </li>
                            @endforeach

                            {{-- reste caché --}}
                            @foreach($recette->ingredients->skip(4) as $ingredient)
                            <li x-show="open"
                                x-transition
                                style="display:none;"
                                class="text-[11px] text-gray-700 flex justify-between border-b border-gray-200 py-1.5">
                                <span class="font-medium">• {{ $ingredient->nom }}</span>
                                <span class="font-black text-orange-600/70">{{ $ingredient->quantite }}</span>
                            </li>
                            @endforeach

                        </ul>

                    </div>

                    {{-- FOOTER --}}
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">

                        <div class="flex items-center gap-2">
                            <div class="user-icon w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center transition-all duration-300">
                                <i data-lucide="user" class="icon-user w-3 h-3 text-orange-500 transition-all duration-300"></i>
                            </div>

                            <span class="text-[10px] font-bold text-gray-800 uppercase">
                                {{ $recette->user->name ?? 'Inconnu' }}
                            </span>
                        </div>

                        @auth
                        <button onclick="likeRecette({{ $recette->id }}, this)" 
                                class="like-btn flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 
                                {{ $isLiked ? 'bg-red-50' : 'bg-gray-100' }}">

                            <i data-lucide="heart"
                               class="w-4 h-4 {{ $isLiked ? 'text-red-500 fill-red-500' : 'text-gray-400' }}"></i>

                            <span class="text-[10px] font-black uppercase 
                                {{ $isLiked ? 'text-red-500' : 'text-gray-500' }}">
                                J'adore
                            </span>

                        </button>
                        @endauth

                    </div>

                </div>
            </div>

        @endforeach

    </div>
</div>

<script>
function likeRecette(id, btn) {
    // Désactiver le bouton pendant la requête
    btn.disabled = true;
    
    // Chercher l'icône (peut être <i> ou <svg>)
    const icon = btn.querySelector('i') || btn.querySelector('svg');
    const span = btn.querySelector('span');
    
    // Vérifier que les éléments existent
    if (!icon || !span) {
        console.error('Éléments non trouvés:', {icon: icon, span: span});
        btn.disabled = false;
        return;
    }
    
    const isLiked = icon.classList.contains('fill-red-500');

    // Animation de l'icône
    if (!isLiked) {
        icon.classList.add('scale-110');
        setTimeout(() => {
            icon.classList.remove('scale-110');
        }, 200);
    }

    // Mise à jour UI immédiate (optimiste)
    if (isLiked) {
        icon.classList.remove('text-red-500', 'fill-red-500');
        icon.classList.add('text-gray-400');
        span.classList.remove('text-red-500');
        span.classList.add('text-gray-500');
        btn.classList.remove('bg-red-50');
        btn.classList.add('bg-gray-100');
    } else {
        icon.classList.add('text-red-500', 'fill-red-500');
        icon.classList.remove('text-gray-400');
        span.classList.add('text-red-500');
        span.classList.remove('text-gray-500');
        btn.classList.remove('bg-gray-100');
        btn.classList.add('bg-red-50');
    }

    // Envoi au serveur
    fetch(`/recettes/${id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Like mis à jour:', data);
    })
    .catch(error => {
        console.error('Erreur lors du like:', error);
        // En cas d'erreur, on pourrait revenir à l'état précédent
        // Mais pour simplifier, on garde l'état UI
    })
    .finally(() => {
        btn.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>

<style>
.like-btn i.scale-110{
animation:heartBeat 0.3s ease-out;
}

@keyframes heartBeat{
0%{transform:scale(1);}
50%{transform:scale(1.3);}
100%{transform:scale(1.1);}
}
</style>

@endsection