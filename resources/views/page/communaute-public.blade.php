@extends('layouts.header')

@section('content')
<div class="max-w-6xl mx-auto px-4 pt-28 pb-16">

    {{-- Hero --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 rounded-[36px] p-8 md:p-12 text-white shadow-2xl shadow-orange-500/30 mb-10">
        <div class="absolute -top-16 -right-16 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-xs font-bold uppercase tracking-widest mb-4">
                <i data-lucide="users" class="w-4 h-4"></i> Communauté OURATABLE
            </span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tighter mb-3">Le monde se retrouve à table</h1>
            <p class="text-white/90 mb-6">Découvrez les publications, astuces et recettes du monde entier partagées par nos membres. Rejoignez-nous pour participer !</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-2xl bg-white text-orange-600 font-bold text-sm hover:scale-105 transition-transform flex items-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Se connecter
                </a>
                <a href="{{ route('login') }}#register" class="px-6 py-3 rounded-2xl bg-orange-700/40 text-white font-bold text-sm hover:bg-orange-700/60 transition-colors flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Créer un compte
                </a>
            </div>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-3 gap-4 mb-10">
        @php
            $stats = [
                ['users', $totalMembres, 'Membres'],
                ['message-square', $totalPosts, 'Publications'],
                ['message-circle', $totalComments, 'Commentaires'],
            ];
        @endphp
        @foreach($stats as $s)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mx-auto mb-2">
                <i data-lucide="{{ $s[0] }}" class="w-5 h-5"></i>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $s[1] }}</div>
            <div class="text-xs text-gray-500">{{ $s[2] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Fil des publications --}}
        <div class="lg:col-span-2 space-y-5">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-tight text-gray-900">
                <i data-lucide="newspaper" class="w-5 h-5 text-orange-500"></i> Dernières publications
            </h2>
            @forelse($posts as $post)
            <article class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-11 h-11 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold overflow-hidden">
                            @if($post->user && $post->user->avatar)
                                <img src="{{ asset($post->user->avatar) }}" class="w-full h-full object-cover" alt="">
                            @else
                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $post->user->name ?? 'Membre' }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }} •
                                <span class="text-orange-500 font-semibold uppercase tracking-wide">{{ $post->type }}</span>
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $post->content }}</p>
                </div>
                @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Publication" class="w-full max-h-96 object-cover">
                @endif
                <div class="px-5 py-3 flex items-center gap-5 text-sm text-gray-500 border-t border-gray-50">
                    <span class="flex items-center gap-1.5"><i data-lucide="heart" class="w-4 h-4 text-rose-500"></i> {{ $post->likes_count }}</span>
                    <span class="flex items-center gap-1.5"><i data-lucide="message-circle" class="w-4 h-4"></i> {{ $post->comments_count }}</span>
                    <a href="{{ route('login') }}" class="ml-auto text-orange-600 font-semibold hover:underline flex items-center gap-1">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i> Connectez-vous pour participer
                    </a>
                </div>
            </article>
            @empty
            <div class="bg-white rounded-3xl border border-gray-100 p-10 text-center text-gray-400">
                Aucune publication pour le moment.
            </div>
            @endforelse
        </div>

        {{-- Recettes populaires --}}
        <aside class="space-y-5">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-tight text-gray-900">
                <i data-lucide="flame" class="w-5 h-5 text-orange-500"></i> Recettes populaires
            </h2>
            <div class="space-y-4">
                @foreach($topRecettes as $r)
                <a href="/" class="block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="h-28 bg-gray-100 overflow-hidden">
                        @if($r->image_path)
                            <img src="{{ asset('storage/' . $r->image_path) }}" alt="{{ $r->titre }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i data-lucide="image" class="w-8 h-8"></i></div>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="font-bold text-gray-800 text-sm truncate">{{ $r->titre }}</p>
                        <p class="text-xs text-gray-400">par {{ $r->user->name ?? 'Chef' }}</p>
                        <div class="flex items-center gap-1 text-rose-500 text-xs mt-1">
                            <i data-lucide="heart" class="w-3 h-3 fill-current"></i> {{ $r->likes_count }} coups de cœur
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <a href="{{ route('recette.semaine') }}" class="block text-center text-orange-600 font-bold text-sm hover:underline">
                Voir la recette de la semaine →
            </a>
        </aside>
    </div>
</div>

@include('layouts.footer')
@endsection
