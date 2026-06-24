@php
    $useUser = auth()->check() && !auth()->user()->isAdmin();
    preg_match('/^\[(.+?)\]\s*(.*)$/', $recette->description, $m);
    $pays = $m[1] ?? null;
    $desc = $m[2] ?? $recette->description;
@endphp
@extends($useUser ? 'layouts.UserHeader' : 'layouts.header')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <a href="{{ $useUser ? '/UserHome' : '/' }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-orange-600 mb-5">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux recettes
    </a>

    {{-- Hero --}}
    <div class="relative rounded-[32px] overflow-hidden shadow-xl mb-8 h-72 md:h-96 bg-gray-200">
        @if($recette->image_path)
            <img src="{{ asset('storage/' . $recette->image_path) }}" alt="{{ $recette->titre }}" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
            @if($pays)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500 text-white text-xs font-bold uppercase tracking-widest mb-3">
                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $pays }}
            </span>
            @endif
            <h1 class="text-3xl md:text-5xl font-black tracking-tighter">{{ $recette->titre }}</h1>
            <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-white/90">
                <span class="flex items-center gap-1.5"><i data-lucide="chef-hat" class="w-4 h-4"></i> {{ $recette->user->name ?? 'Chef OuraTable' }}</span>
                <span class="flex items-center gap-1.5"><i data-lucide="heart" class="w-4 h-4 text-rose-400 fill-rose-400"></i> <span id="like-count">{{ $recette->likes_count }}</span> coups de cœur</span>
                <span class="flex items-center gap-1.5"><i data-lucide="list" class="w-4 h-4"></i> {{ $recette->ingredients->count() }} ingrédients</span>
            </div>
        </div>
    </div>

    {{-- Action like --}}
    <div class="flex justify-end mb-6">
        @if($useUser)
            <button id="like-btn" data-id="{{ $recette->id }}" data-liked="{{ $isLiked ? '1' : '0' }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all {{ $isLiked ? 'bg-rose-500 text-white' : 'bg-rose-50 text-rose-600 hover:bg-rose-100' }}">
                <i data-lucide="heart" class="w-4 h-4 {{ $isLiked ? 'fill-current' : '' }}"></i>
                <span id="like-label">{{ $isLiked ? 'Aimé' : "J'aime cette recette" }}</span>
            </button>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-orange-500 text-white font-bold text-sm hover:bg-orange-600">
                <i data-lucide="log-in" class="w-4 h-4"></i> Connectez-vous pour aimer
            </a>
        @endif
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        {{-- Ingrédients --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sticky top-28">
                <h2 class="flex items-center gap-2 text-lg font-black tracking-tight text-gray-900 mb-4">
                    <i data-lucide="shopping-basket" class="w-5 h-5 text-orange-500"></i> Ingrédients
                </h2>
                <ul class="space-y-3">
                    @forelse($recette->ingredients as $ing)
                    <li class="flex items-center justify-between gap-3 pb-3 border-b border-gray-50 last:border-0">
                        <span class="flex items-center gap-2 text-gray-700 text-sm">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-orange-400 shrink-0"></i> {{ $ing->nom }}
                        </span>
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg whitespace-nowrap">{{ $ing->quantite }}</span>
                    </li>
                    @empty
                    <li class="text-gray-400 text-sm">Aucun ingrédient renseigné.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Description + instructions --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-lg font-black tracking-tight text-gray-900 mb-3">
                    <i data-lucide="info" class="w-5 h-5 text-orange-500"></i> À propos
                </h2>
                <p class="text-gray-600 leading-relaxed">{{ $desc }}</p>
            </div>
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8">
                <h2 class="flex items-center gap-2 text-lg font-black tracking-tight text-gray-900 mb-4">
                    <i data-lucide="utensils-crossed" class="w-5 h-5 text-orange-500"></i> Préparation
                </h2>
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $recette->instructions }}</div>
            </div>
        </div>
    </div>

    {{-- Autres recettes --}}
    @if($autres->count())
    <div class="mt-12">
        <h2 class="flex items-center gap-2 text-xl font-black tracking-tight text-gray-900 mb-5">
            <i data-lucide="flame" class="w-5 h-5 text-orange-500"></i> D'autres recettes à découvrir
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($autres as $r)
            <a href="{{ route('recette.page', $r->id) }}" class="block rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-28 bg-gray-100 overflow-hidden">
                    @if($r->image_path)<img src="{{ asset('storage/' . $r->image_path) }}" class="w-full h-full object-cover" alt="">@endif
                </div>
                <div class="p-3">
                    <p class="font-bold text-gray-800 text-sm truncate">{{ $r->titre }}</p>
                    <p class="text-xs text-gray-400">par {{ $r->user->name ?? 'Chef' }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if($useUser)
<script>
(function () {
    const btn = document.getElementById('like-btn');
    if (!btn) return;
    btn.addEventListener('click', function () {
        btn.disabled = true;
        fetch('{{ url('/recettes') }}/' + btn.dataset.id + '/like', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const liked = d.liked;
                document.getElementById('like-count').textContent = d.likes_count;
                document.getElementById('like-label').textContent = liked ? 'Aimé' : "J'aime cette recette";
                btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all ' + (liked ? 'bg-rose-500 text-white' : 'bg-rose-50 text-rose-600 hover:bg-rose-100');
                btn.querySelector('i').classList.toggle('fill-current', liked);
            }
        })
        .finally(() => { btn.disabled = false; });
    });
})();
</script>
@endif

@unless($useUser)
@include('layouts.footer')
@endunless
@endsection
