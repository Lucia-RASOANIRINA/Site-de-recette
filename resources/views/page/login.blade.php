@extends('layouts.header')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="absolute top-0 -left-4 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute bottom-0 -right-4 w-72 h-72 bg-orange-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

    <div class="max-w-md w-full relative group">
        <div class="bg-white/80 backdrop-blur-xl rounded-[40px] shadow-2xl border border-white p-10 transition-all duration-700 ease-in-out transform" id="auth-container">
            
            <div id="login-form" class="transition-opacity duration-500">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Oura<span class="text-orange-500">Table</span></h2>
                    <p class="text-gray-400 text-sm mt-2 font-medium">Bon retour à notre table !</p>
                </div>

                <form action="/login" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <input type="email" name="email" required placeholder="Email" 
                            class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-all shadow-sm">
                    </div>
                    <div>
                        <input type="password" name="password" required placeholder="Mot de passe" 
                            class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-all shadow-sm">
                    </div>
                    <button type="submit" class="w-full bg-orange-500 text-white font-black py-4 rounded-2xl hover:bg-orange-600 shadow-lg shadow-orange-200 transition-all active:scale-95 uppercase tracking-widest text-sm">
                        Se connecter
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <button onclick="toggleAuth()" class="text-gray-400 text-xs font-bold hover:text-orange-500 transition-colors uppercase tracking-widest">
                        Je n'ai pas de compte ? <span class="text-orange-500 ml-1">S'inscrire</span>
                    </button>
                </div>
            </div>

            <div id="register-form" class="hidden opacity-0 transition-opacity duration-500">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Rejoindre la<span class="text-orange-500">Table</span></h2>
                    <p class="text-gray-400 text-sm mt-2 font-medium">Créez votre profil culinaire.</p>
                </div>

                <form action="/register" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" required placeholder="Nom complet" 
                        class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all shadow-sm">
                    
                    <input type="text" name="phone" required placeholder="Téléphone" 
                        class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all shadow-sm">
                    
                    <input type="email" name="email" required placeholder="Email (Optionnel)" 
                        class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all shadow-sm">
                    
                    <input type="password" name="password" required placeholder="Mot de passe" 
                        class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all shadow-sm">

                    <button type="submit" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-orange-500 shadow-lg transition-all active:scale-95 uppercase tracking-widest text-sm mt-4">
                        Créer mon compte
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <button onclick="toggleAuth()" class="text-gray-400 text-xs font-bold hover:text-orange-500 transition-colors uppercase tracking-widest">
                        Déjà membre ? <span class="text-orange-500 ml-1">Se connecter</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleAuth() {
    const login = document.getElementById('login-form');
    const register = document.getElementById('register-form');
    const container = document.getElementById('auth-container');

    // Animation de transition
    container.classList.add('scale-95', 'opacity-50');
    
    setTimeout(() => {
        login.classList.toggle('hidden');
        login.classList.toggle('opacity-0');
        register.classList.toggle('hidden');
        register.classList.toggle('opacity-0');
        container.classList.remove('scale-95', 'opacity-50');
    }, 300);
}
</script>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
</style>
@endsection