@extends('layouts.UserHeader')

@section('content')
@include('partials.search-results')
<style>
    [x-cloak] { display: none !important; }

    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 10s linear infinite;
    }

    /* Modal styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        border-radius: 2rem;
        max-width: 900px;
        width: 100%;
        max-height: 95vh;
        display: flex;
        flex-direction: column;
        animation: modalSlideIn 0.3s ease-out;
        position: relative;
        overflow: hidden;
    }
    
    /* Scrollbar orange à l'intérieur du modal */
    .modal-scroll-area {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        max-height: calc(95vh - 70px);
    }
    
    .modal-scroll-area::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-scroll-area::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 10px;
    }
    
    .modal-scroll-area::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
    
    .modal-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }
    
    /* Header de la modal avec le titre et le bouton fermeture */
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem 0.5rem 2rem;
        flex-shrink: 0;
        border-bottom: 1px solid #f3f4f6;
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .modal-header-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-header-title .brand {
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -0.05em;
        line-height: 1;
        display: flex;
        align-items: center;
        gap: 0;
    }
    
    .modal-header-title .brand .oura {
        color: #f97316;
    }
    
    .modal-header-title .brand .table {
        color: #1f2937;
    }
    
    .modal-header-title .subtitle {
        font-size: 0.6rem;
        font-weight: 600;
        color: #9ca3af;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        margin-left: 0.5rem;
    }
    
    .modal-close {
        background: #f3f4f6;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .modal-close:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }
    
    .modal-close i {
        width: 20px;
        height: 20px;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .modal-image-wrapper {
        width: 100%;
        border-radius: 1.5rem;
        overflow: hidden;
        margin-top: 1rem;
        margin-bottom: 0;
        background: transparent;
        max-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-image-wrapper img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: contain;
        display: block;
        border-radius: 1.5rem;
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f2937;
        text-align: center;
        margin-bottom: 0.25rem;
    }
    
    .modal-desc-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #f97316;
        font-weight: 700;
        margin-top: 0.5rem;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .modal-desc {
        color: #6b7280;
        font-style: italic;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        text-align: center;
    }
    
    .modal-date {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #f9fafb;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f3f4f6;
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .modal-date i {
        width: 16px;
        height: 16px;
        color: #f97316;
    }
    
    .modal-date span {
        font-weight: 600;
        color: #1f2937;
    }
    
    .modal-instructions {
        margin-bottom: 1.5rem;
    }
    
    .modal-instructions h4 {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #f97316;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-instructions h4 i {
        width: 16px;
        height: 16px;
    }
    
    .instruction-step {
        display: flex;
        gap: 0.75rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .instruction-step:last-child {
        border-bottom: none;
    }
    
    .step-number {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.7rem;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
    }
    
    .step-text {
        flex: 1;
        color: #374151;
        font-size: 0.9rem;
        line-height: 1.6;
        padding-top: 0.1rem;
    }
    
    .modal-ingredients {
        border-top: 2px solid #f3f4f6;
        padding-top: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .modal-ingredients h4 {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #f97316;
        font-weight: 800;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-ingredients h4 i {
        width: 16px;
        height: 16px;
    }
    
    .ingredient-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #fef3c7;
        padding: 0.3rem 0.8rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        color: #374151;
        border: 1px solid #fde68a;
        margin: 0.2rem;
    }
    
    .ingredient-tag .qty {
        font-weight: 700;
        color: #f97316;
        font-size: 0.7rem;
    }
    
    .ingredient-tag i {
        width: 12px;
        height: 12px;
        color: #f97316;
    }

    /* Style pour le carrousel - les images non actives ne sont pas cliquables */
    .carousel-image {
        cursor: default;
        transition: all 0.3s ease;
    }
    
    .carousel-image.active {
        cursor: pointer;
    }
    
    .carousel-image.active:hover {
        transform: scale(1.03);
        border-color: #f97316;
    }
</style>

<!-- AlpineJS pour gérer afficher/cacher -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
     x-data="{ 
            currentIndex: 0, 
            showLoginModal: false,
            total: {{ $recettes->count() }},
            next() { this.currentIndex = (this.currentIndex + 1) % this.total },
            prev() { this.currentIndex = (this.currentIndex - 1 + this.total) % this.total }
         }">
    
    {{-- SECTION HAUTE --}}
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
                            <div x-show="currentIndex === {{ $index }}"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-50"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute -top-4 -left-4 bg-gray-900 text-white p-3 rounded-2xl shadow-xl z-[60]">
                                <i data-lucide="award" class="w-5 h-5 text-orange-400"></i>
                            </div>

                            <img src="{{ asset('storage/' . $recette->image_path) }}"
                                class="carousel-image w-48 h-64 md:w-64 md:h-80 object-cover rounded-[2.5rem] shadow-2xl border-[10px] border-white transition-all duration-300 cursor-pointer group-hover:scale-[1.03] group-hover:border-orange-400"
                                :class="currentIndex === {{ $index }} ? 'active' : ''"
                                @click="currentIndex === {{ $index }} ? window.location.href='{{ route('recette.page', $recette->id) }}' : null">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- GRILLE DES RECETTES --}}
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
                    
                    <div class="absolute inset-y-0 left-0 w-3/4 bg-orange-500 rounded-r-[40px] transition-colors duration-500 group-hover:bg-orange-600"></div>

                    <div class="relative z-10 w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden transition-transform duration-500 group-hover:scale-105 group-hover:rotate-2 cursor-pointer" onclick="window.location.href='{{ route('recette.page', $recette->id) }}'">

                        <div class="absolute inset-2 rounded-full bg-orange-900/10 blur-xl transition-all duration-500 group-hover:bg-orange-900/20"></div>

                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                            class="relative w-full h-full object-cover rounded-full border-[6px] border-white shadow-lg transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">

                        <div class="absolute -top-2 -right-2 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1 border border-orange-50">
                            <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                            <span class="text-[10px] font-black text-gray-800">{{ number_format($rating,1) }}</span>
                        </div>

                    </div>
                </div>

                {{-- CONTENU --}}
                <div class="w-3/5 p-6 pl-2 flex flex-col justify-center">

                    <div class="mb-3">
                        <h3 class="font-bold text-lg text-center text-gray-900 truncate">{{ $recette->titre }}</h3>
                        <p class="text-gray-500 text-[11px] italic line-clamp-1">
                            "{{ $recette->description }}"
                        </p>
                    </div>

                    <div x-data="{ open:false }" class="mb-4 bg-gray-50 p-4 rounded-[24px] border border-gray-100">

                        <div class="flex items-center justify-between mb-3">

                            <div class="flex items-center gap-2">
                                <i data-lucide="utensils" class="w-3.5 h-3.5 text-orange-500"></i>
                                <span class="text-[10px] font-black text-gray-500 uppercase">
                                    {{ $totalIngredients }} ingrédients
                                </span>
                            </div>

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

                            @foreach($recette->ingredients->take(4) as $ingredient)
                            <li class="text-[11px] text-gray-700 flex justify-between border-b border-gray-200 pb-1.5">
                                <span class="font-medium">• {{ $ingredient->nom }}</span>
                                <span class="font-black text-orange-600/70">{{ $ingredient->quantite }}</span>
                            </li>
                            @endforeach

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

{{-- MODAL --}}
<div id="recipeModal" class="modal-overlay" onclick="if(event.target === this) closeModal()">
    <div class="modal-content">
        <!-- Header avec titre et bouton fermeture -->
        <div class="modal-header">
            <div class="modal-header-title">
                <div class="brand">
                    <span class="oura">OURA</span><span class="table">TABLE</span>
                </div>
                <div class="subtitle">ASSIETTE OUVERTE</div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Zone de contenu scrollable avec scrollbar orange -->
        <div class="modal-scroll-area">
            <h2 id="modalTitle" class="modal-title"></h2>
            
            <span class="modal-desc-label">Description</span>
            <p id="modalDesc" class="modal-desc"></p>
            
            <div class="modal-date">
                <i data-lucide="calendar"></i>
                Publiée le : <span id="modalCreatedAt"></span>
            </div>
            
            <div class="modal-instructions">
                <h4><i data-lucide="book-open"></i> Étapes de préparation</h4>
                <div id="modalInstructions"></div>
            </div>
            
            <div class="modal-ingredients">
                <h4><i data-lucide="utensils"></i> Ingrédients</h4>
                <div id="modalIngredients"></div>
            </div>
            
            <div class="modal-image-wrapper">
                <img id="modalImage" src="" alt="">
            </div>
        </div>
    </div>
</div>

<script>
function likeRecette(id, btn) {
    btn.disabled = true;
    
    const icon = btn.querySelector('i') || btn.querySelector('svg');
    const span = btn.querySelector('span');
    
    if (!icon || !span) {
        console.error('Éléments non trouvés:', {icon: icon, span: span});
        btn.disabled = false;
        return;
    }
    
    const isLiked = icon.classList.contains('fill-red-500');

    if (!isLiked) {
        icon.classList.add('scale-110');
        setTimeout(() => {
            icon.classList.remove('scale-110');
        }, 200);
    }

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
    })
    .finally(() => {
        btn.disabled = false;
    });
}

function splitInstructions(text) {
    if (!text) return [];
    var steps = text.split(/[.,;]\s+|\n+/).filter(function(step) {
        return step.trim() !== '';
    });
    if (steps.length <= 1 && !text.includes('.') && !text.includes(',') && !text.includes(';')) {
        return [text.trim()];
    }
    return steps.map(function(step) { return step.trim(); });
}

function openModal(id) {
    fetch('/recettes/' + id + '/details')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Erreur:', data.message);
                return;
            }
            
            const recette = data.recette;
            
            document.getElementById('modalImage').src = data.image_url || '/storage/' + recette.image_path;
            document.getElementById('modalTitle').textContent = recette.titre;
            document.getElementById('modalDesc').textContent = recette.description || '';
            
            const createdDate = recette.created_at ? new Date(recette.created_at).toLocaleDateString('fr-FR', {
                day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
            }) : 'Date inconnue';
            
            document.getElementById('modalCreatedAt').textContent = createdDate;
            
            const steps = splitInstructions(recette.instructions);
            let instructionsHtml = '';
            if (steps.length > 0) {
                instructionsHtml = steps.map(function(step, index) {
                    return '<div class="instruction-step"><div class="step-number">' + (index + 1) + '</div><div class="step-text">' + step + '</div></div>';
                }).join('');
            } else {
                instructionsHtml = '<p style="color: #9ca3af; text-align: center; padding: 1rem 0;">Aucune instruction disponible</p>';
            }
            document.getElementById('modalInstructions').innerHTML = instructionsHtml;
            
            let ingredientsHtml = '';
            if (recette.ingredients && recette.ingredients.length > 0) {
                ingredientsHtml = recette.ingredients.map(function(ing) {
                    return '<span class="ingredient-tag"><i data-lucide="circle"></i> ' + ing.nom + ' <span class="qty">' + ing.quantite + '</span></span>';
                }).join('');
            } else {
                ingredientsHtml = '<span style="color: #9ca3af; font-size: 0.9rem;">Aucun ingrédient</span>';
            }
            document.getElementById('modalIngredients').innerHTML = ingredientsHtml;
            
            document.getElementById('recipeModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            if (window.lucide) lucide.createIcons();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function closeModal() {
    document.getElementById('recipeModal').classList.remove('active');
    document.body.style.overflow = 'auto';
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