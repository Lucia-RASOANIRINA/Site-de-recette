@extends('layouts.header')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    /* Animations spectaculaires */
    @keyframes floatIn {
        0% {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            filter: blur(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    }

    @keyframes modalPop {
        0% {
            opacity: 0;
            transform: scale(0.8) rotate(-5deg);
        }
        50% {
            transform: scale(1.05) rotate(2deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0);
        }
    }

    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.2); }
        50% { transform: scale(0.95); }
        75% { transform: scale(1.1); }
    }

    @keyframes slideInMessage {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes borderGlow {
        0%, 100% { border-color: rgba(249, 115, 22, 0.2); box-shadow: 0 0 15px rgba(249, 115, 22, 0.1); }
        50% { border-color: rgba(249, 115, 22, 0.5); box-shadow: 0 0 25px rgba(249, 115, 22, 0.2); }
    }

    @keyframes subtleFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .animate-float-in {
        animation: floatIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-modal-pop {
        animation: modalPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .animate-heart-beat {
        animation: heartBeat 0.6s ease-in-out;
    }

    .animate-slide-message {
        animation: slideInMessage 0.3s ease-out forwards;
    }

    .animate-border-glow {
        animation: borderGlow 2s ease-in-out infinite;
    }

    .animate-subtle-float {
        animation: subtleFloat 4s ease-in-out infinite;
    }

    /* Effet d'image plus petit avec cadre élégant */
    .image-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 2rem;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
    }

    .image-frame {
        position: absolute;
        inset: -2px;
        border: 3px solid white;
        border-radius: 2.2rem;
        z-index: 20;
        pointer-events: none;
        box-shadow: inset 0 0 20px rgba(249, 115, 22, 0.2);
    }

    .image-backdrop {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #f97316 0%, #fbbf24 100%);
        border-radius: 2rem;
        transform: rotate(2deg) scale(1.05);
        opacity: 0.25;
        transition: all 0.6s ease;
        filter: blur(5px);
    }

    .image-backdrop.second {
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        transform: rotate(-1deg) scale(1.03);
        opacity: 0.15;
        filter: blur(8px);
    }

    .image-main {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 1.8rem;
        transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 10;
    }

    .image-container:hover .image-main {
        transform: scale(1.08);
    }

    .image-container:hover .image-backdrop {
        transform: rotate(4deg) scale(1.15);
        opacity: 0.35;
        filter: blur(10px);
    }

    .image-container:hover .image-backdrop.second {
        transform: rotate(-2deg) scale(1.12);
        opacity: 0.25;
    }

    .post-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .post-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.05), transparent);
        transition: left 0.8s ease;
        pointer-events: none;
    }

    .post-card:hover::before {
        left: 100%;
    }

    .post-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 50px -20px rgba(249, 115, 22, 0.4);
    }

    .interaction-btn {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        background: white;
        border: 1px solid #f1f1f1;
    }

    .interaction-btn:hover {
        transform: scale(1.1);
        border-color: #f97316;
        background: white;
    }

    .interaction-btn:active {
        transform: scale(0.95);
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }

    .wave-effect {
        position: relative;
        overflow: hidden;
    }
    .wave-effect::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }
    .wave-effect:active::after {
        width: 300px;
        height: 300px;
    }

    .login-prompt {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.03) 0%, rgba(249, 115, 22, 0.08) 100%);
        border: 1.5px solid rgba(249, 115, 22, 0.2);
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }
    .login-prompt:hover {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.06) 0%, rgba(249, 115, 22, 0.12) 100%);
        border-color: rgba(249, 115, 22, 0.4);
        transform: translateY(-2px);
    }

    .last-comment-card {
        background: white;
        border: 1px solid rgba(249, 115, 22, 0.1);
        border-radius: 1.2rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .last-comment-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(to bottom, #f97316, #fbbf24);
        opacity: 0.5;
    }
    .last-comment-card:hover {
        border-color: rgba(249, 115, 22, 0.3);
        transform: translateX(5px);
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(249, 115, 22, 0.1);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        border-color: rgba(249, 115, 22, 0.3);
        transform: translateY(-2px);
    }

    .filter-btn {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .filter-btn:hover:not(.active) {
        border-color: #f97316;
        color: #f97316;
        transform: translateY(-2px);
    }

    .comment-message-box {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 40px -15px rgba(249, 115, 22, 0.3);
        border: 1px solid rgba(249, 115, 22, 0.2);
        position: relative;
        overflow: hidden;
    }
    .comment-message-box::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at top right, rgba(249, 115, 22, 0.05), transparent 70%);
        z-index: 0;
    }

    @keyframes scaleX {
        0% { transform: scaleX(0); }
        100% { transform: scaleX(1); }
    }
    
    .animation-delay-1000 {
        animation-delay: 1s;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen bg-gray-50 pb-20"
     x-data="{ 
        activeFilter: 'all',
        showLoginModal: false,
        showCommentModal: false,
        selectedAction: '',
        showComments: false,
        isGuest: true,
        hoveredPost: null
     }">
    
   {{-- MODALE PRINCIPALE (pour like/share) --}}
<div x-show="showLoginModal" 
     x-cloak
     @keydown.escape.window="showLoginModal = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-md"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div @click.away="showLoginModal = false" 
         class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden animate-modal-pop border border-orange-100">
        
        <div class="h-2 bg-gradient-to-r from-orange-500 via-orange-400 to-yellow-500"></div>
        
        <div class="px-8 pt-8 pb-6 text-center">
            <div class="relative mx-auto mb-6 w-20 h-20">
                <div class="absolute inset-0 bg-orange-200 rounded-2xl rotate-45 animate-pulse"></div>
                <div class="absolute inset-0 bg-orange-500 rounded-2xl rotate-12 flex items-center justify-center">
                    <div x-show="selectedAction === 'like'" x-cloak>
                        <i data-lucide="heart" class="w-10 h-10 text-white animate-heart-beat" stroke-width="1.5"></i>
                    </div>
                    <div x-show="selectedAction === 'share'" x-cloak>
                        <i data-lucide="share-2" class="w-10 h-10 text-white" stroke-width="1.5"></i>
                    </div>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                <span x-text="selectedAction === 'like' ? 'Un like qui compte' : 'Partagez avec vos amis'"></span>
            </h3>
            
            <p class="text-gray-500 mb-8">
                Rejoignez les <span class="font-bold text-orange-500">{{ $totalMembres }}</span> membres de la communauté
            </p>
        </div>
        
        <div class="px-8 pb-8 space-y-3">
            <a href="{{ route('login') }}#login" 
               class="group relative block w-full py-4 bg-orange-500 text-white font-bold rounded-xl overflow-hidden transition-all hover:shadow-xl hover:shadow-orange-500/25 hover:-translate-y-1 wave-effect">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-5 h-5 group-hover:rotate-12 transition-transform" stroke-width="1.5"></i>
                    Se connecter
                </span>
            </a>
            
            <a href="{{ route('login') }}#register" 
               class="group relative block w-full py-4 bg-gray-100 text-gray-700 font-bold rounded-xl overflow-hidden transition-all hover:bg-gray-200 hover:-translate-y-1 wave-effect">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-orange-500 group-hover:scale-110 transition-transform" stroke-width="1.5"></i>
                    Créer un compte
                </span>
            </a>
            
            <button @click="showLoginModal = false" 
                    class="w-full py-4 text-sm font-medium text-gray-400 hover:text-orange-500 hover:text-base transition-all duration-300 ease-in-out group">
                <span class="relative inline-block">Continuer sans rejoindre</span>
            </button>
        </div>
        
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
            <p class="text-xs text-gray-400 text-center flex items-center justify-center gap-2">
                <i data-lucide="users" class="w-4 h-4" stroke-width="1.5"></i>
                +{{ $totalMembres }} membres ont déjà rejoint
            </p>
        </div>
    </div>
</div>

   {{-- MODALE COMMENTAIRES --}}
<div x-show="showCommentModal" 
     x-cloak
     @keydown.escape.window="showCommentModal = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-md"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div @click.away="showCommentModal = false" 
         class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-modal-pop border border-orange-100 comment-message-box">
        
        <div class="h-2 bg-gradient-to-r from-orange-500 via-orange-400 to-yellow-500"></div>
        
        <div class="px-8 pt-8 pb-6 text-center">
            <div class="relative mx-auto mb-6 w-24 h-24">
                <div class="absolute inset-0 bg-orange-100 rounded-full animate-pulse"></div>
                <div class="absolute inset-2 bg-orange-200 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center z-10 shadow-inner">
                    <i data-lucide="message-circle" class="w-10 h-10 text-orange-500"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-lg">
                    <span>{{ $totalComments }}</span>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Rejoignez la conversation !</h3>
            <p class="text-gray-500 mb-4">{{ $totalComments }} commentaires partagés</p>
        </div>
        
        <div class="px-8 pb-8 space-y-3">
            <a href="{{ route('login') }}#login" class="w-full py-4 bg-orange-500 text-white font-bold rounded-xl block text-center">Se connecter</a>
            <a href="{{ route('login') }}#register" class="w-full py-4 bg-gray-100 text-gray-700 font-bold rounded-xl block text-center">Créer un compte</a>
            <button @click="showCommentModal = false" class="w-full py-3 text-sm text-gray-400 hover:text-orange-500">Continuer sans rejoindre</button>
        </div>
    </div>
</div>

    {{-- HERO SECTION --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
    <div class="relative">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-orange-200/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-orange-300/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        
        <div class="relative flex flex-col lg:flex-row lg:items-end justify-between gap-8">
            <div class="animate-float-in max-w-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="relative group/badge">
                        <div class="absolute inset-0 bg-orange-500 rounded-2xl blur-md opacity-30"></div>
                        <div class="relative bg-gradient-to-br from-orange-500 to-orange-600 p-2.5 rounded-2xl shadow-lg">
                            <i data-lucide="users" class="w-5 h-5 text-white"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight">
                            Le Fil <span class="text-orange-500">des Gourmets</span>
                        </h1>
                        <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center animate-bounce">
                            <i data-lucide="chef-hat" class="w-6 h-6 text-orange-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="relative pl-6 border-l-4 border-orange-500/30 bg-gradient-to-r from-orange-50/50 to-transparent p-4 rounded-r-2xl">
                    <p class="text-gray-600 text-base md:text-lg leading-relaxed flex items-center gap-3">
                        <i data-lucide="sparkles" class="w-5 h-5 text-orange-500"></i>
                        <span>Le point de rencontre des Cœurs Gourmands. Échangez, partagez et inspirez-vous !</span>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-4 animate-float-in" style="animation-delay: 0.1s">
                <div class="group/stat relative bg-white/80 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-lg border border-gray-100 hover:border-orange-500 transition-all hover:-translate-y-2">
                    <div class="relative text-center">
                        <div class="flex items-center justify-center gap-1 mb-1">
                            <i data-lucide="file-text" class="w-4 h-4 text-orange-500"></i>
                            <span class="text-[12px] font-medium text-gray-400">Publications</span>
                        </div>
                        <span class="block text-3xl font-black text-gray-900">{{ $totalPosts }}</span>
                    </div>
                </div>
                
                <div class="group/stat relative bg-white/80 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-lg border border-gray-100 hover:border-orange-500 transition-all hover:-translate-y-2">
                    <div class="relative text-center">
                        <div class="flex items-center justify-center gap-1 mb-1">
                            <i data-lucide="users" class="w-4 h-4 text-orange-500"></i>
                            <span class="text-[12px] font-medium text-gray-400">Passionnés</span>
                        </div>
                        <span class="block text-3xl font-black text-gray-900">{{ $totalMembres }}</span>
                    </div>
                </div>
                
                <div class="group/stat relative bg-white/80 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-lg border border-gray-100 hover:border-orange-500 transition-all hover:-translate-y-2">
                    <div class="relative text-center">
                        <div class="flex items-center justify-center gap-1 mb-1">
                            <i data-lucide="message-circle" class="w-4 h-4 text-orange-500"></i>
                            <span class="text-[12px] font-medium text-gray-400">Commentaires</span>
                        </div>
                        <span class="block text-3xl font-black text-gray-900">{{ $totalComments }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTRES --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-8">
    <div class="flex justify-center">
        <div class="flex flex-wrap gap-5 justify-center">
            <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-orange-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-500'" class="px-6 py-3 rounded-full text-sm font-bold transition-all">Tous</button>
            <button @click="activeFilter = 'realisation'" :class="activeFilter === 'realisation' ? 'bg-green-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:border-green-500'" class="px-6 py-3 rounded-full text-sm font-bold transition-all">Réalisations</button>
            <button @click="activeFilter = 'question'" :class="activeFilter === 'question' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-500'" class="px-6 py-3 rounded-full text-sm font-bold transition-all">Questions</button>
            <button @click="activeFilter = 'defi'" :class="activeFilter === 'defi' ? 'bg-purple-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:border-purple-500'" class="px-6 py-3 rounded-full text-sm font-bold transition-all">Défis</button>
        </div>
    </div>
</div>

    {{-- GRILLE DE POSTS --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @forelse($posts as $post)
        @php
            $lastComment = $post->comments->last();
            $postCommentsCount = $post->comments_count ?? $post->comments->count();
        @endphp
        <div x-data="{ 
                isHovered: false,
                likesCount: {{ $post->likes_count ?? $post->likes->count() }}
             }"
             x-show="activeFilter === 'all' || activeFilter === '{{$post->type}}'"
             x-transition:enter="transition-all duration-500 ease-out"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             @mouseenter="isHovered = true"
             @mouseleave="isHovered = false"
             class="post-card bg-white rounded-3xl border border-gray-100 overflow-hidden"
             :class="{ 'shadow-xl': isHovered }">
            
            <div class="relative h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent"
                 :style="{ width: isHovered ? '100%' : '0%' }"
                 style="transition: width 0.6s ease"></div>
            
            <div class="p-6 md:p-8">
                {{-- EN-TÊTE UTILISATEUR --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4 group/user">
                        <div class="relative">
                            <div class="relative w-14 h-14 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 text-lg">{{ $post->user->name }}</h4>
                                <i data-lucide="badge-check" class="w-4 h-4 text-orange-500"></i>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- BADGE TYPE --}}
                    <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2
                        {{ $post->type == 'question' ? 'bg-blue-50 text-blue-600' : '' }}
                        {{ $post->type == 'realisation' ? 'bg-green-50 text-green-600' : '' }}
                        {{ $post->type == 'defi' ? 'bg-purple-50 text-purple-600' : '' }}">
                        @if($post->type == 'question') 
                            <i data-lucide="search-code" class="w-4 h-4"></i> Question
                        @elseif($post->type == 'realisation') 
                            <i data-lucide="cooking-pot" class="w-4 h-4"></i> Réalisation
                        @else 
                            <i data-lucide="trophy" class="w-4 h-4"></i> Défi
                        @endif
                    </span>
                </div>

                {{-- CONTENU --}}
                <div class="mb-6 flex gap-3">
                    <i data-lucide="message-square" class="w-5 h-5 text-orange-400 flex-shrink-0 mt-1"></i>
                    <p class="text-gray-700 text-lg leading-relaxed">{{ $post->content }}</p>
                </div>

                {{-- IMAGE --}}
                @if($post->image_path)
                <div class="mb-6 flex justify-center">
                    <div class="image-container group/image">
                        <div class="image-backdrop"></div>
                        <div class="image-backdrop second"></div>
                        <div class="image-frame"></div>
                        <img src="{{ asset('storage/'.$post->image_path) }}" class="image-main" alt="Post image">
                    </div>
                </div>
                @endif

                {{-- BARRE D'INTERACTIONS --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button @click="showLoginModal = true; selectedAction = 'like'" class="interaction-btn flex items-center gap-2 px-4 py-2 rounded-xl transition-all">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        <span class="font-bold text-sm" x-text="likesCount"></span>
                    </button>

                    <button @click="showCommentModal = true" class="interaction-btn flex items-center gap-2 px-4 py-2 rounded-xl transition-all">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span class="font-bold text-sm">{{ $postCommentsCount }}</span>
                    </button>

                    <button @click="showLoginModal = true; selectedAction = 'share'" class="interaction-btn flex items-center gap-2 px-4 py-2 rounded-xl transition-all ml-auto">
                        <i data-lucide="share-2" class="w-4 h-4"></i>
                    </button>
                </div>

                {{-- DERNIER COMMENTAIRE --}}
                @if($lastComment)
                <div class="mt-4 last-comment-card p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-xl flex items-center justify-center text-xs font-bold text-orange-600 flex-shrink-0">
                            {{ substr($lastComment->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-gray-700">{{ $lastComment->user->name }}</span>
                                <span class="text-[10px] text-gray-400">{{ $lastComment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $lastComment->content }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
            <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i data-lucide="utensils" class="w-10 h-10 text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-400 mb-2">Rien à se mettre sous la dent...</h3>
            <p class="text-gray-400">Soyez le premier à partager une création !</p>
        </div>
        @endforelse
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
    });
</script>
@endsection