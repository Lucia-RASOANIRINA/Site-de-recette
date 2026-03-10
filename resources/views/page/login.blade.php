@extends('layouts.header')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="h-[calc(100vh-70px)] flex items-center justify-center px-4 relative overflow-hidden bg-gray-50 font-sans">
    
    <div class="absolute top-0 -left-4 w-64 h-64 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob"></div>
    <div class="absolute bottom-0 -right-4 w-64 h-64 bg-orange-50 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>

    <div class="max-w-4xl w-full h-[460px] relative shadow-2xl rounded-[35px] overflow-hidden bg-white/90 backdrop-blur-xl border border-white flex" id="main-auth-box">
        
        <div class="w-full flex relative">
            
            <div id="login-section" class="w-1/2 p-6 flex flex-col justify-center transition-all duration-700">
                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center mb-1">Connexion</h2>
                <p class="text-gray-400 text-[11px] font-medium text-center mb-4">Bon retour à notre table !</p>
                @if(session('success'))
                    <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-r-2xl shadow-sm animate-bounce-short">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-orange-500 p-2 rounded-full">
                                <i data-lucide="utensils" class="w-4 h-4 text-white"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-xs font-black text-orange-500 uppercase tracking-widest">
                                    Inscription réussie !
                                </h3>
                                <p class="text-[10px] text-gray-400  font-medium  mt-0.5">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('login') }}" method="POST" class="space-y-3 max-w-[280px] mx-auto w-full">
                    @csrf
                    <div class="relative flex items-center">
                        <input type="email" name="email" value="{{ !old('name') ? old('email') : '' }}" required placeholder="Email" 
                            class="w-full pr-12 pl-5 py-4 bg-gray-100/50 border @error('email') border-red-400 @else border-gray-100 @enderror rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm shadow-sm transition-all">
                        <i data-lucide="mail" class="absolute right-4 w-5 h-5 text-gray-400"></i>
                    </div>
                    @if ($errors->has('email') && !old('name'))
                        <p class="text-[9px] text-red-500 font-bold mt-1 ml-2">{{ $errors->first('email') }}</p>
                    @endif
                    
                    <div class="relative flex items-center">
                        <input type="password" id="login-pass" name="password" required placeholder="Mot de passe" 
                            class="w-full pr-12 pl-5 py-4 bg-gray-100/50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm shadow-sm transition-all">
                        <button type="button" onclick="togglePass('login-pass', 'eye-login')" class="absolute right-4 text-gray-400 hover:text-orange-500">
                            <i id="eye-login" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <button type="submit" class="w-full bg-orange-500 text-white font-black py-4 rounded-2xl hover:bg-orange-600 shadow-lg transition-all active:scale-95 uppercase tracking-widest text-xs mt-2">
                        Se connecter
                    </button>
                </form>
            </div>

            <div id="register-section" class="w-1/2 p-6 flex flex-col justify-center transition-all duration-700">
                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center mb-1">S'inscrire</h2>
                <p class="text-gray-400 text-[11px] text-center mb-4">Créez votre profil culinaire.</p>
                
                <form action="/register" method="POST" class="space-y-2 max-w-[320px] mx-auto w-full">
                    @csrf
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="relative flex items-center">
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nom" class="w-full pr-10 pl-4 py-3 bg-gray-100/50 border @error('name') border-red-400 @else border-gray-100 @enderror rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <i data-lucide="user" class="absolute right-3 w-4 h-4 text-gray-400"></i>
                            </div>
                            @error('name') <p class="text-[8px] text-red-500 mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <div class="relative flex items-center">
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Tél" class="w-full pr-10 pl-4 py-3 bg-gray-100/50 border @error('phone') border-red-400 @else border-gray-100 @enderror rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <i data-lucide="phone" class="absolute right-3 w-4 h-4 text-gray-400"></i>
                            </div>
                            @error('phone') <p class="text-[8px] text-red-500 mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="relative flex items-center">
                        <input type="email" name="email" value="{{ old('name') ? old('email') : '' }}" required placeholder="Email" class="w-full pr-12 pl-4 py-3 bg-gray-100/50 border @if($errors->has('email') && old('name')) border-red-400 @else border-gray-100 @enderror rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                        <i data-lucide="mail" class="absolute right-4 w-5 h-5 text-gray-400"></i>
                    </div>
                    @if($errors->has('email') && old('name'))
                        <p class="text-[8px] text-red-500 mt-1 ml-1">{{ $errors->first('email') }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="relative flex items-center">
                                <input type="password" id="reg-pass" name="password" required placeholder="MDP" class="w-full pr-10 pl-4 py-3 bg-gray-100/50 border @error('password') border-red-400 @else border-gray-100 @enderror rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <button type="button" onclick="togglePass('reg-pass', 'eye-reg')" class="absolute right-3"><i id="eye-reg" data-lucide="eye" class="w-4 h-4 text-gray-400"></i></button>
                            </div>
                        </div>
                        <div>
                            <div class="relative flex items-center">
                                <input type="password" name="password_confirmation" required placeholder="Confirmer" class="w-full pr-10 pl-4 py-3 bg-gray-100/50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <i data-lucide="lock" class="absolute right-3 w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    @error('password') <p class="text-[8px] text-red-500 mt-1 ml-1 text-center font-bold">{{ $message }}</p> @enderror

                    <button type="submit" class="w-full bg-orange-500 text-white font-black py-4 rounded-2xl hover:bg-orange-600 transition-all uppercase tracking-widest text-[10px] mt-1 shadow-md">
                        Créer mon compte
                    </button>
                </form>
            </div>
        </div>

        <div id="overlay-panel" class="absolute top-0 right-0 w-1/2 h-full bg-orange-500 transition-all duration-700 ease-in-out z-20 flex flex-col justify-center items-center text-white px-8 text-center shadow-2xl">
            <div id="overlay-to-reg" class="transition-all duration-500">
                <div class="p-3 bg-white/20 rounded-full mb-4 inline-block">
                    <i data-lucide="chef-hat" class="w-10 h-10 text-white"></i>
                </div>
                <h3 class="text-2xl font-black uppercase mb-2">Oura<span>Table</span></h3>
                <p class="text-[11px] mb-6 opacity-90 font-medium italic">Pas encore de compte ?</p>
                <button onclick="movePanel('left')" class="border-2 border-white px-10 py-3 rounded-full font-bold uppercase text-[10px] tracking-widest hover:bg-white hover:text-orange-500 transition-all">
                    S'inscrire
                </button>
            </div>

            <div id="overlay-to-log" class="hidden transition-all duration-500">
                <div class="p-3 bg-white/20 rounded-full mb-4 inline-block text-center">
                    <i data-lucide="utensils-crossed" class="w-10 h-10 text-white"></i>
                </div>
                <h3 class="text-2xl font-black uppercase mb-2">Bienvenue !</h3>
                <p class="text-[11px] mb-6 opacity-90 font-medium italic">Déjà membre ?</p>
                <button onclick="movePanel('right')" class="border-2 border-white px-10 py-3 rounded-full font-bold uppercase text-[10px] tracking-widest hover:bg-white hover:text-orange-500 transition-all">
                    Se connecter
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialisation des icônes au chargement
lucide.createIcons();

function movePanel(direction) {
    const overlay = document.getElementById('overlay-panel');
    const toReg = document.getElementById('overlay-to-reg');
    const toLog = document.getElementById('overlay-to-log');

    if (direction === 'left') {
        overlay.style.transform = 'translateX(-100%)';
        setTimeout(() => {
            toReg.classList.add('hidden');
            toLog.classList.remove('hidden');
            lucide.createIcons(); // Recharge les icônes après changement de DOM
        }, 300);
    } else {
        overlay.style.transform = 'translateX(0%)';
        setTimeout(() => {
            toLog.classList.add('hidden');
            toReg.classList.remove('hidden');
            lucide.createIcons();
        }, 300);
    }
}

function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === "password") {
        input.type = "text";
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = "password";
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}

// AUTO-DETECTION DES ERREURS POUR LE PLACEMENT DU PANNEAU
document.addEventListener("DOMContentLoaded", function() {
    // Si on a des erreurs d'inscription (indiquées par la présence d'un 'old name')
    // ou des erreurs spécifiques au téléphone/password, on glisse à gauche.
    @if($errors->has('phone') || $errors->has('name') || $errors->has('password') || (old('name') && $errors->has('email')))
        movePanel('left');
    @endif
});

// Détection de l'ancre dans l'URL pour basculer automatiquement
function checkHash() {
    const hash = window.location.hash;
    if (hash === '#register') {
        movePanel('left'); // Déplace le panneau vers l'inscription
    } else if (hash === '#login') {
        movePanel('right'); // Déplace le panneau vers la connexion
    }
}

// Exécuter au chargement de la page
document.addEventListener("DOMContentLoaded", function() {
    checkHash();
    
    // Écouter aussi les changements d'ancre si l'utilisateur est déjà sur la page
    window.addEventListener('hashchange', checkHash);

    // Ton code existant pour la détection des erreurs Laravel
    @if($errors->has('phone') || $errors->has('name') || $errors->has('password') || (old('name') && $errors->has('email')))
        movePanel('left');
    @endif
});
</script>

<style>
    body { overflow: hidden; }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(20px, -20px) scale(1.05); }
        66% { transform: translate(-10px, 10px) scale(0.95); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 10s infinite; }
    #overlay-panel { transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1); }
</style>
@endsection