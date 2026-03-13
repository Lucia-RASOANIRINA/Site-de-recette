@extends('layouts.UserHeader')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    .shimmer-effect {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        background-size: 1000px 100%;
        animation: shimmer 3s infinite;
    }

    @keyframes gradient-rotate {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .gradient-rotate {
        background: linear-gradient(-45deg, #f97316, #fb923c, #f59e0b, #f97316);
        background-size: 300% 300%;
        animation: gradient-rotate 8s ease infinite;
    }

    @keyframes border-pulse {
        0%, 100% { border-color: rgba(249, 115, 22, 0.2); }
        50% { border-color: rgba(249, 115, 22, 0.8); }
    }
    .animate-border-pulse {
        animation: border-pulse 2s ease-in-out infinite;
    }

    .card-3d {
        transform-style: preserve-3d;
        perspective: 1000px;
    }
    .card-3d:hover {
        transform: rotateX(5deg) rotateY(5deg);
    }

    .text-stroke {
        -webkit-text-stroke: 1px rgba(249, 115, 22, 0.3);
        text-stroke: 1px rgba(249, 115, 22, 0.3);
    }
</style>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
     x-data="{ 
        activeFilter: 'all',
        showChallengeModal: false,
        selectedPost: null,
        showComments: null,
        hoveredPost: null
     }">
    
    {{-- EN-TÊTE SPECTACULAIRE --}}
    <div class="relative mb-20">
        {{-- Éléments décoratifs flottants --}}
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-orange-200/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-20 -right-10 w-60 h-60 bg-orange-300/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        <div class="absolute bottom-0 left-1/3 w-80 h-80 bg-yellow-200/20 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
        
        <div class="relative text-center">
            <div class="inline-flex items-center gap-3 bg-orange-50/80 backdrop-blur-sm px-6 py-3 rounded-full mb-8 border border-orange-200/50 shadow-xl">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-orange-800 font-bold text-sm uppercase tracking-[0.3em]">360 membres connectés</span>
                <i data-lucide="users" class="w-4 h-4 text-orange-600"></i>
            </div>
            
            <h1 class="text-7xl md:text-8xl font-black text-gray-900 tracking-tighter mb-6 relative inline-block">
                <span class="relative z-10 bg-gradient-to-r from-orange-600 via-orange-500 to-yellow-500 bg-clip-text text-transparent">
                    COMMUNAUTÉ
                </span>
                <span class="absolute -top-6 -right-6 text-9xl opacity-10 rotate-12">🍳</span>
            </h1>
            
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Là où les passions culinaires se rencontrent, 
                <span class="text-orange-500 font-bold italic">les secrets s'échangent</span> 
                et les talents s'épanouissent
            </p>
            
            <div class="flex items-center justify-center gap-4 mt-8">
                <div class="h-1 w-20 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-full"></div>
                <span class="text-xs font-black uppercase tracking-[0.3em] text-gray-400">PARTAGE & INSPIRATION</span>
                <div class="h-1 w-20 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full"></div>
            </div>
        </div>
    </div>

    {{-- DÉFIS & CONCOURS --}}
    @if(isset($challenge) && $challenge && $challenge->status == 'active')
    <div class="grid md:grid-cols-2 gap-8 mb-20">
        {{-- Défi de la semaine --}}
        <div class="group relative bg-gradient-to-br from-orange-500 to-red-500 rounded-[50px] p-1 shadow-2xl hover:shadow-orange-500/30 transition-all duration-500 hover:-translate-y-2">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-400 rounded-[50px] blur-xl opacity-50 group-hover:opacity-80 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-orange-500 to-red-500 rounded-[50px] p-8 overflow-hidden">
                {{-- Éléments décoratifs --}}
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-yellow-400/20 rounded-full blur-2xl"></div>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform duration-500">
                        <span class="text-4xl">🔥</span>
                    </div>
                    <div>
                        <span class="text-white/60 text-xs font-black uppercase tracking-[0.2em]">Défi actif</span>
                        <h2 class="text-3xl font-black text-white">Défi de la semaine</h2>
                    </div>
                </div>
                
                <p class="text-white/90 text-lg mb-6 leading-relaxed">{{$challenge->description}}</p>
                
                @if(!empty($challenge->ingredients_imposes))
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 mb-6 border border-white/20">
                    <p class="text-white font-bold mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        Ingrédients mystère :
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $ingredients = is_string($challenge->ingredients_imposes) ? 
                                json_decode($challenge->ingredients_imposes, true) : 
                                $challenge->ingredients_imposes;
                        @endphp
                        @if(is_array($ingredients))
                            @foreach($ingredients as $ingredient)
                                <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white font-medium border border-white/30 hover:scale-110 transition-transform cursor-default">
                                    {{$ingredient}} ✦
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif
                
                <a href="/community/challenge/{{$challenge->id}}" 
                   class="group/btn inline-flex items-center gap-4 bg-white text-orange-600 px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <span>Participer maintenant</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover/btn:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>

        {{-- Concours Ingrédient Mystère --}}
        <div class="group relative bg-gradient-to-br from-purple-500 to-pink-500 rounded-[50px] p-1 shadow-2xl hover:shadow-purple-500/30 transition-all duration-500 hover:-translate-y-2">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 rounded-[50px] blur-xl opacity-50 group-hover:opacity-80 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-purple-500 to-pink-500 rounded-[50px] p-8 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-pink-400/20 rounded-full blur-2xl"></div>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform duration-500">
                        <span class="text-4xl">🎯</span>
                    </div>
                    <div>
                        <span class="text-white/60 text-xs font-black uppercase tracking-[0.2em]">Concours</span>
                        <h2 class="text-3xl font-black text-white">Ingrédient Mystère</h2>
                    </div>
                </div>
                
                <p class="text-white/90 text-lg mb-6 leading-relaxed">
                    3 ingrédients imposés, 1 recette originale. Faites preuve de créativité !
                </p>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 mb-6 border border-white/20">
                    <p class="text-white/80 text-sm mb-2">🏆 Prix spécial :</p>
                    <p class="text-white font-bold text-xl">Badge "Chef Créatif" exclusif</p>
                </div>
                
                <a href="/community/concours" 
                   class="group/btn inline-flex items-center gap-4 bg-white text-purple-600 px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <span>Découvrir les participations</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover/btn:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- FORMULAIRE DE PUBLICATION --}}
    <div class="relative mb-16 group/form">
        <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-[50px] blur-xl opacity-30 group-hover/form:opacity-60 transition-opacity"></div>
        <div class="relative bg-white rounded-[40px] shadow-2xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-8 py-6 border-b border-orange-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center text-white text-2xl shadow-lg">
                        ✏️
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800">Partagez votre passion</h3>
                        <p class="text-sm text-gray-500">Une réalisation, une question, une participation...</p>
                    </div>
                </div>
            </div>
            
            <form action="/community/post" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                <textarea name="content" 
                    class="w-full border-2 border-gray-100 rounded-[30px] p-6 mb-6 focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300 text-gray-700 placeholder:text-gray-400 text-lg"
                    placeholder="Qu'avez-vous cuisiné aujourd'hui ? Une question technique ? ..."
                    rows="4" required></textarea>
                
                <div class="grid md:grid-cols-3 gap-6 mb-6">
                    <div class="relative group/select">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-wider mb-3">Type de publication</label>
                        <select name="type" class="w-full border-2 border-gray-100 rounded-2xl p-4 appearance-none bg-white focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300 font-medium" required>
                            <option value="realisation" class="py-2">📸 Réalisation personnelle</option>
                            <option value="question" class="py-2">❓ Question technique</option>
                            <option value="defi" class="py-2">🏆 Participation au défi</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 bottom-5 w-5 h-5 text-gray-400 pointer-events-none"></i>
                    </div>
                    
                    <div class="relative group/file">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-wider mb-3">Photo (optionnelle)</label>
                        <div class="relative">
                            <input type="file" name="image" class="w-full border-2 border-gray-100 rounded-2xl p-4 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-orange-50 file:text-orange-600 file:font-bold hover:file:bg-orange-100 transition-all" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="group/btn w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-black py-4 px-6 rounded-2xl text-sm uppercase tracking-widest shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-3">
                            <span>Publier</span>
                            <i data-lucide="send" class="w-5 h-5 group-hover/btn:translate-x-2 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- FILTRES AVANCÉS --}}
    <div class="flex flex-wrap items-center justify-between gap-6 mb-12">
        <div class="flex flex-wrap gap-3">
            <button @click="activeFilter = 'all'" 
                    :class="activeFilter === 'all' ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/30 scale-110' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-8 py-4 rounded-2xl font-bold text-sm transition-all duration-300 flex items-center gap-2">
                <span>🔥</span> Tous les posts
            </button>
            <button @click="activeFilter = 'realisation'" 
                    :class="activeFilter === 'realisation' ? 'bg-green-500 text-white shadow-xl shadow-green-500/30 scale-110' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-8 py-4 rounded-2xl font-bold text-sm transition-all duration-300 flex items-center gap-2">
                <span>📸</span> Réalisations
            </button>
            <button @click="activeFilter = 'question'" 
                    :class="activeFilter === 'question' ? 'bg-blue-500 text-white shadow-xl shadow-blue-500/30 scale-110' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-8 py-4 rounded-2xl font-bold text-sm transition-all duration-300 flex items-center gap-2">
                <span>❓</span> Questions
            </button>
            <button @click="activeFilter = 'defi'" 
                    :class="activeFilter === 'defi' ? 'bg-purple-500 text-white shadow-xl shadow-purple-500/30 scale-110' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-8 py-4 rounded-2xl font-bold text-sm transition-all duration-300 flex items-center gap-2">
                <span>🏆</span> Défis
            </button>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-400">{{ $posts->count() }} publications</span>
            <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6 text-orange-600"></i>
            </div>
        </div>
    </div>

    {{-- FLUX DE POSTS SPECTACULAIRE --}}
    <div class="space-y-8">
        @forelse($posts as $post)
        <div x-data="{ liked: false, likesCount: {{ $post->likes_count ?? $post->likes->count() }}, showComments: false }"
             x-show="activeFilter === 'all' || activeFilter === '{{$post->type}}'"
             x-transition:enter="transition-all duration-500 ease-out"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             @mouseenter="hoveredPost = {{$post->id}}"
             @mouseleave="hoveredPost = null"
             class="group/post relative bg-white rounded-[50px] shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden border border-gray-100"
             :class="{'scale-[1.02] shadow-2xl': hoveredPost === {{$post->id}}}">
            
            {{-- Overlay gradient au survol --}}
            <div class="absolute inset-0 bg-gradient-to-r from-orange-500/0 via-transparent to-orange-500/0 opacity-0 group-hover/post:opacity-20 transition-opacity duration-1000 pointer-events-none"></div>
            
            {{-- Bandeau type --}}
            <div class="absolute top-8 right-8 z-10">
                @if($post->type == "question")
                    <span class="bg-blue-500 text-white px-6 py-3 rounded-full text-sm font-black shadow-xl flex items-center gap-2">
                        <span>❓</span> Question technique
                    </span>
                @elseif($post->type == "defi")
                    <span class="bg-purple-500 text-white px-6 py-3 rounded-full text-sm font-black shadow-xl flex items-center gap-2">
                        <span>🏆</span> Participation défi
                    </span>
                @else
                    <span class="bg-green-500 text-white px-6 py-3 rounded-full text-sm font-black shadow-xl flex items-center gap-2">
                        <span>📸</span> Réalisation
                    </span>
                @endif
            </div>

            <div class="p-8">
                {{-- En-tête du post --}}
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-orange-500 rounded-2xl blur-xl opacity-30"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-2xl">
                                {{strtoupper(substr($post->user->name, 0, 2))}}
                            </div>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-800 text-lg">{{$post->user->name}}</h4>
                            <div class="flex items-center gap-2 text-sm text-gray-400">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                <span>{{$post->created_at->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Bouton follow --}}
                    <button class="px-6 py-3 border-2 border-orange-500 text-orange-500 rounded-2xl font-black text-sm hover:bg-orange-500 hover:text-white transition-all duration-300">
                        + Suivre
                    </button>
                </div>

                {{-- Contenu --}}
                <div class="mb-8">
                    <p class="text-gray-700 text-lg leading-relaxed">{{$post->content}}</p>
                </div>

                {{-- Image --}}
                @if($post->image_path)
                <div class="mb-8 rounded-[40px] overflow-hidden shadow-2xl group/image">
                    <img src="{{asset('storage/'.$post->image_path)}}" 
                         class="w-full max-h-[500px] object-cover transition-transform duration-700 group-hover/image:scale-110" 
                         alt="Post image">
                </div>
                @endif

                {{-- Barre d'interactions --}}
                <div class="flex items-center gap-8 mb-8">
                    <form action="/community/like/{{$post->id}}" method="POST" @submit.prevent="liked = !liked; $el.submit()">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-3 px-6 py-3 rounded-2xl transition-all duration-300"
                                :class="liked ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600 hover:bg-red-50'">
                            <i data-lucide="heart" class="w-6 h-6" :class="liked ? 'fill-red-600' : ''"></i>
                            <span class="font-bold" x-text="likesCount"></span>
                            <span class="text-sm">likes</span>
                        </button>
                    </form>

                    <button @click="showComments = !showComments" 
                            class="flex items-center gap-3 px-6 py-3 bg-gray-50 hover:bg-blue-50 rounded-2xl text-gray-600 hover:text-blue-600 transition-all duration-300 group/btn">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                        <span class="font-bold">{{ $post->comments_count ?? $post->comments->count() }}</span>
                        <span class="text-sm">commentaires</span>
                    </button>

                    <button class="flex items-center gap-3 px-6 py-3 bg-gray-50 hover:bg-yellow-50 rounded-2xl text-gray-600 hover:text-yellow-600 transition-all duration-300">
                        <i data-lucide="share-2" class="w-6 h-6"></i>
                        <span class="text-sm">Partager</span>
                    </button>
                </div>

                {{-- Commentaires existants --}}
                <div x-show="showComments" 
                     x-collapse
                     class="bg-gray-50 rounded-[40px] p-6 mb-6">
                    
                    <h5 class="font-black text-gray-700 mb-4 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-orange-500"></i>
                        Commentaires et conseils
                    </h5>
                    
                    <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar pr-4">
                        @forelse($post->comments as $comment)
                        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow transition-all">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-yellow-400 rounded-xl flex items-center justify-center text-white font-black flex-shrink-0">
                                    {{strtoupper(substr($comment->user->name, 0, 1))}}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-gray-800">{{$comment->user->name}}</span>
                                        @if(isset($comment->astuce) && $comment->astuce)
                                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-black">✨ Astuce</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600">{{$comment->content}}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center py-4">Aucun commentaire pour le moment</p>
                        @endforelse
                    </div>
                </div>

                {{-- Formulaire de commentaire --}}
                <form action="/community/comment" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="post_id" value="{{$post->id}}">
                    
                    <div class="relative group/input">
                        <input type="text" 
                            name="content" 
                            placeholder="Écrivez un commentaire ou un conseil..."
                            class="w-full border-2 border-gray-100 rounded-3xl py-4 px-6 pr-36 focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300"
                            required>
                        
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex gap-2">
                            <label class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-xl text-sm font-medium cursor-pointer hover:bg-orange-100 transition-colors">
                                <input type="checkbox" name="is_astuce" value="1" class="sr-only">
                                <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                                <span>Astuce</span>
                            </label>
                            
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white/80 backdrop-blur-sm rounded-[60px] p-16 text-center shadow-2xl border border-gray-100">
            <div class="w-32 h-32 bg-gradient-to-br from-orange-400 to-yellow-400 rounded-[30px] flex items-center justify-center mx-auto mb-8 animate-float">
                <span class="text-5xl">🍳</span>
            </div>
            <h3 class="text-3xl font-black text-gray-800 mb-4">Encore aucune publication</h3>
            <p class="text-gray-500 text-lg mb-8">Soyez le premier à partager votre passion culinaire !</p>
            <button class="bg-orange-500 text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:shadow-2xl hover:-translate-y-1 transition-all">
                Créer le premier post
            </button>
        </div>
        @endforelse
    </div>
</div>

{{-- Styles additionnels --}}
<style>
    .animation-delay-1000 {
        animation-delay: 1s;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #f97316, #f59e0b);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #f97316;
    }
</style>

{{-- Inclusion des icônes Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection