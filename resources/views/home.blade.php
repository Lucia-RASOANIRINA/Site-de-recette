@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Nos Recettes</h1>

    <!-- SCROLL HORIZONTAL -->
    <div class="flex space-x-6 overflow-x-auto py-4">
        @foreach($recettes as $recette)
            <div class="bg-white rounded-xl shadow-md min-w-[250px] flex-shrink-0">
                <img src="{{ asset('storage/'.$recette->image_path) }}" alt="{{ $recette->titre }}" class="rounded-t-xl w-full h-40 object-cover">
                <div class="p-4">
                    <h2 class="font-bold text-lg mb-2">{{ $recette->titre }}</h2>
                    <p class="text-gray-600 text-sm line-clamp-3">{{ $recette->description }}</p>
                    <div class="mt-3">
                        <span class="text-green-500 font-semibold">{{ $recette->user->name }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection