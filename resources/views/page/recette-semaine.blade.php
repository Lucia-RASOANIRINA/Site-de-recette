@php $isAdminView = auth()->check() && auth()->user()->isAdmin(); @endphp
@extends($isAdminView ? 'layouts.AdminHeader' : 'layouts.header')

@section('title', 'Recette de la semaine')

@section('content')
<div class="max-w-6xl mx-auto px-4 {{ $isAdminView ? 'pt-4' : 'pt-28' }} pb-12">

    {{-- En-tête --}}
    <div class="text-center mb-10">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-100 text-orange-600 text-xs font-black uppercase tracking-widest mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3 9.24 3 10.91 3.81 12 5.08 13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            Classement de la semaine
        </span>
        <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-gray-900">La Recette <span class="text-orange-500 italic">Coup de Cœur</span></h1>
        <p class="text-gray-500 mt-3 max-w-xl mx-auto">Les recettes les plus aimées des 7 derniers jours. Votez pour vos préférées — aucun compte nécessaire !</p>
    </div>

    @if($errors->any())
        <div class="max-w-xl mx-auto mb-6 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center">{{ $errors->first() }}</div>
    @endif

    {{-- Formulaire de vote (nom de recette + email) --}}
    <div class="max-w-2xl mx-auto bg-white border border-gray-100 rounded-3xl shadow-sm p-6 md:p-8 mb-12">
        <h2 class="font-bold text-gray-900 text-lg mb-1">Votez pour votre recette préférée</h2>
        <p class="text-gray-500 text-sm mb-5">Indiquez le nom de la recette et votre email. Un email = un vote par recette.</p>
        <form action="{{ route('recette.vote') }}" method="POST" class="flex flex-col md:flex-row gap-3">
            @csrf
            <input type="text" name="recette_nom" placeholder="Nom de la recette..." required value="{{ old('recette_nom') }}" list="recette-list"
                class="flex-1 border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
            <input type="email" name="email" placeholder="Votre email..." required value="{{ old('email') }}"
                class="flex-1 border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none">
            <button type="submit" class="px-6 py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm whitespace-nowrap transition-colors">J'aime</button>
        </form>
        <datalist id="recette-list">
            @foreach($recettes as $r)<option value="{{ $r->titre }}"></option>@endforeach
        </datalist>
    </div>

    @if($top)
        {{-- Podium N°1 --}}
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-[36px] p-8 md:p-10 text-white shadow-2xl shadow-orange-500/30 mb-10 relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                <div class="w-24 h-24 rounded-3xl bg-white/15 flex items-center justify-center text-5xl font-black shrink-0">1</div>
                <div class="flex-1 text-center md:text-left">
                    <p class="text-white/70 text-xs uppercase tracking-widest font-bold mb-1">Recette de la semaine</p>
                    <h2 class="text-3xl font-black tracking-tight">{{ $top->titre }}</h2>
                    <p class="text-white/80 mt-1">par {{ $top->user->name ?? 'Chef OuraTable' }}</p>
                    <div class="flex items-center justify-center md:justify-start gap-4 mt-3 text-sm">
                        <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3 9.24 3 10.91 3.81 12 5.08 13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg> {{ $top->week_likes }} likes</span>
                        <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.787 1.401 8.168L12 18.896l-7.335 3.857 1.401-8.168L.132 9.211l8.2-1.193z"/></svg> {{ $top->week_votes }} votes</span>
                    </div>
                </div>
                <div class="text-center shrink-0">
                    <div class="text-5xl font-black">{{ $top->week_score }}</div>
                    <div class="text-[10px] uppercase tracking-widest text-white/70">points</div>
                </div>
            </div>
        </div>

        {{-- Reste du classement --}}
        <div class="grid gap-4">
            @foreach($ranking as $i => $r)
                @if($i === 0) @continue @endif
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl font-black shrink-0">{{ $i + 1 }}</div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 truncate">{{ $r->titre }}</h3>
                        <p class="text-xs text-gray-500">par {{ $r->user->name ?? 'Chef OuraTable' }} • {{ $r->week_likes }} likes • {{ $r->week_votes }} votes</p>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-2xl font-black text-orange-500">{{ $r->week_score }}</div>
                        <div class="text-[9px] uppercase tracking-widest text-gray-400">points</div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-20 h-20 rounded-full bg-orange-50 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            </div>
            <p class="text-gray-500 font-medium">Aucun vote cette semaine pour le moment.</p>
            <p class="text-gray-400 text-sm mt-1">Soyez le premier à aimer une recette !</p>
        </div>
    @endif
</div>

@unless($isAdminView)
@include('layouts.footer')
@endunless
@endsection
