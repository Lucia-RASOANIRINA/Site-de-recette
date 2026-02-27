<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recette App</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- NAVBAR -->
    <nav class="bg-white shadow fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex-shrink-0 flex items-center text-2xl font-bold text-green-500">
                    RecetteApp
                </div>
                <div class="flex space-x-4 items-center">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-green-500">Accueil</a>
                    <a href="{{ url('/login') }}" class="text-gray-700 hover:text-green-500">Connexion</a>
                    <a href="{{ url('/register') }}" class="text-gray-700 hover:text-green-500">Inscription</a>
                    <a href="{{ url('/about') }}" class="text-gray-700 hover:text-green-500">À propos</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        @yield('content')
    </main>

</body>
</html>