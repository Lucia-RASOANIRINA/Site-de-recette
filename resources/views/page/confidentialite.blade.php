@extends('layouts.header')

@section('content')
<div class="max-w-4xl mx-auto px-4 pt-28 pb-16">
    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="shield-check" class="w-8 h-8"></i>
        </div>
        <h1 class="text-4xl font-black tracking-tighter text-gray-900">Politique de <span class="text-orange-500 italic">Confidentialité</span></h1>
        <p class="text-gray-500 mt-2">Dernière mise à jour : {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-10 space-y-8">
        @php
            $sections = [
                ['database', 'Données que nous collectons', "Lors de votre inscription, nous collectons votre nom, votre adresse email et, facultativement, votre téléphone, ville, spécialité culinaire et photo de profil. Lorsque vous publiez des recettes ou des messages, ces contenus sont enregistrés sur la plateforme."],
                ['target', "Utilisation de vos données", "Vos données servent à gérer votre compte, afficher votre profil public, vous permettre d'échanger avec la communauté, et vous envoyer des emails liés à votre compte (vérification, messages de l'administration)."],
                ['lock', "Sécurité", "Vos mots de passe sont chiffrés (hachage) et ne sont jamais stockés en clair. Nous mettons en œuvre des mesures techniques raisonnables pour protéger vos informations."],
                ['mail', "Communications", "L'administration peut vous contacter par email ou via la messagerie interne. Vous recevez uniquement des messages en lien avec OURATABLE."],
                ['eye-off', "Vote anonyme", "Les visiteurs sans compte peuvent voter pour la recette de la semaine. Seuls le nom de la recette et l'email saisis sont conservés, afin d'éviter les votes en double."],
                ['user-x', "Vos droits", "Vous pouvez à tout moment modifier vos informations depuis votre profil, ou demander la suppression de votre compte en contactant l'administration."],
            ];
        @endphp
        @foreach($sections as $i => $s)
        <section class="flex gap-4">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                <i data-lucide="{{ $s[0] }}" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 mb-1">{{ $i + 1 }}. {{ $s[1] }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $s[2] }}</p>
            </div>
        </section>
        @endforeach

        <div class="border-t border-gray-100 pt-6 text-sm text-gray-500">
            Pour toute question relative à vos données, contactez-nous à
            <a href="mailto:luciarasoanirina8@gmail.com" class="text-orange-600 font-semibold">luciarasoanirina8@gmail.com</a>.
        </div>
    </div>
</div>

@include('layouts.footer')
@endsection
