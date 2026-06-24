@extends('layouts.header')

@section('content')
<div class="max-w-4xl mx-auto px-4 pt-28 pb-16">
    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="scale" class="w-8 h-8"></i>
        </div>
        <h1 class="text-4xl font-black tracking-tighter text-gray-900">Mentions <span class="text-orange-500 italic">Légales</span></h1>
        <p class="text-gray-500 mt-2">Informations légales relatives au site OURATABLE</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-10 space-y-8">
        @php
            $sections = [
                ['building', "Éditeur du site", "Le site OURATABLE (« L'art de bien manger ») est une plateforme communautaire de partage de recettes de cuisine du monde. Éditrice et responsable de la publication : Lucia Rasoanirina."],
                ['mail', "Contact", "Pour toute question, vous pouvez écrire à luciarasoanirina8@gmail.com."],
                ['server', "Hébergement", "Le site est hébergé par le prestataire d'hébergement de l'éditrice. Les données sont stockées de manière sécurisée."],
                ['copyright', "Propriété intellectuelle", "Les recettes, textes et images publiés par les membres restent la propriété de leurs auteurs. Toute reproduction sans autorisation est interdite. La marque et l'identité visuelle OURATABLE sont protégées."],
                ['users', "Contenus des membres", "Les membres sont responsables des contenus qu'ils publient. OURATABLE est un site modéré : tout contenu inapproprié peut être supprimé par l'administration."],
                ['alert-triangle', "Responsabilité", "L'éditrice s'efforce d'assurer l'exactitude des informations mais ne saurait être tenue responsable des erreurs, omissions ou résultats des recettes publiées par les membres."],
            ];
        @endphp
        @foreach($sections as $s)
        <section class="flex gap-4">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                <i data-lucide="{{ $s[0] }}" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 mb-1">{{ $s[1] }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $s[2] }}</p>
            </div>
        </section>
        @endforeach
    </div>
</div>

@include('layouts.footer')
@endsection
