@extends('layouts.header')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-16">
       <h2 class="text-gray-900 tracking-tighter">
            <div class="text-2xl font-black tracking-tighter font-medium text-orange-500 italic">
                Notre <span class=" text-gray-800">Top séléction</span>
            </div>
        </h2>
        <p class="text-gray-400 font-medium text-sm mt-4 tracking-[0.1em]">
            L'excellence de la **Table Oura** partagée par la communauté.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pt-10">
        @foreach($recettes as $recette)
            @php
                // Logique dynamique
                $likesCount = $recette->likes_count ?? 0; // Supposant un withCount('likes')
                $rating = min(5, ($likesCount * 0.1) + 3.5);
            @endphp
            
            <div class="group relative bg-white rounded-[40px] shadow-sm border border-gray-50 p-8 pt-0 transition-all duration-500 hover:shadow-xl hover:-translate-y-2">
                
                <div class="relative -mt-12 mb-6 flex justify-center">
                    <div class="relative w-48 h-48 rounded-full transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-2 rounded-full bg-black/5 blur-xl group-hover:bg-black/15 transition-all"></div>
                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                             class="relative w-full h-full object-cover rounded-full border-[8px] border-white">
                    </div>

                    <div class="absolute top-8 right-0 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1">
                        <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                        <span class="text-[10px] font-black">{{ number_format($rating, 1) }}</span>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h3 class="font-bold text-xl text-gray-800 mb-2 truncate">{{ $recette->titre }}</h3>
                    <p class="text-gray-400 text-xs mb-6 line-clamp-2 italic">"{{ $recette->description }}"</p>
                    
                    <div class="flex items-center justify-between border-t border-gray-50 pt-5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            {{ $recette->ingredients->count() }} Ingrédients
                        </span>
                        
                        <button onclick="likeRecette({{ $recette->id }}, this)" 
                                class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-50 hover:bg-red-50 transition-all group/btn">
                            <span class="text-[10px] font-bold text-gray-500 group-hover/btn:text-red-500 uppercase">J'adore</span>
                            <i data-lucide="heart" class="w-4 h-4 text-gray-300 group-hover/btn:text-red-500"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function likeRecette(id, element) {
    // Ici tu feras ton appel fetch('/like/' + id)
    // Pour l'instant, animation visuelle :
    const heart = element.querySelector('i');
    heart.classList.toggle('fill-red-500');
    heart.classList.toggle('text-red-500');
    
    // Animation de "pop"
    heart.style.transform = "scale(1.3)";
    setTimeout(() => heart.style.transform = "scale(1)", 200);
}
</script>
@endsection