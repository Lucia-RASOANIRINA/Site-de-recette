@extends('layouts.header')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="h-[calc(100vh-70px)] w-full flex items-center justify-center px-4 relative overflow-hidden bg-gray-50 font-sans">
    
    <!-- Blobs de fond -->
    <div class="absolute top-0 -left-4 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob"></div>
    <div class="absolute bottom-0 -right-4 w-72 h-72 bg-orange-50 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>

    <!-- Conteneur Principal -->
    <div class="max-w-4xl w-full h-[540px] md:h-[480px] relative shadow-2xl rounded-[35px] overflow-hidden bg-white/90 backdrop-blur-xl border border-white flex flex-col md:flex-row" id="main-auth-box">
        
        <div class="w-full flex relative h-full overflow-hidden">
            
            <!-- SECTION CONNEXION -->
            <div id="login-section" class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-center transition-all h-full overflow-hidden">
                <div class="max-w-[280px] mx-auto w-full">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center mb-1">Connexion</h2>
                    <p class="text-gray-400 text-[11px] font-medium text-center mb-4">Bon retour à notre table !</p>
                    
                    <form action="{{ route('login') }}" method="POST" class="space-y-3 w-full" id="login-form">
                        @csrf
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="relative flex items-center group custom-tooltip" data-error="">
                            <input type="email" id="login-email" name="email" value="{{ old('email') }}" required placeholder="Email *" 
                                class="validate-field w-full pr-12 pl-5 py-3.5 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm shadow-inner transition-all">
                            <i data-lucide="mail" class="absolute right-4 w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                        </div>
                        
                        <div class="relative flex items-center group custom-tooltip" data-error="">
                            <input type="password" id="login-pass" name="password" required placeholder="Mot de passe *" 
                                class="validate-field w-full pr-12 pl-5 py-3.5 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm shadow-inner transition-all">
                            <button type="button" onclick="togglePass('login-pass', 'eye-login')" class="absolute right-4 text-gray-400 hover:text-orange-500">
                                <i id="eye-login" data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <button type="submit" class="w-full bg-orange-500 text-white font-black py-3.5 rounded-2xl hover:bg-orange-600 shadow-lg shadow-orange-500/10 transition-all active:scale-98 uppercase tracking-widest text-xs mt-2">
                            Se connecter
                        </button>
                    </form>

                    <!-- Switch Mobile -->
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center md:hidden">
                        <button onclick="movePanel('left')" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-orange-50 text-orange-600 font-bold text-[11px] tracking-wide active:scale-95 transition-all">
                            Créer un compte <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION INSCRIPTION -->
            <div id="register-section" class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-center transition-all hidden md:flex h-full overflow-hidden">
                <div class="max-w-[300px] mx-auto w-full">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center mb-1">S'inscrire</h2>
                    <p class="text-gray-400 text-[11px] text-center mb-4">Créez votre profil culinaire.</p>
                    
                    <form action="{{ route('register') }}" method="POST" id="register-form" class="space-y-2.5 w-full">
                        @csrf
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative custom-tooltip" data-error="">
                                <input type="text" id="reg-name" name="name" value="{{ old('name') }}" required placeholder="Nom *" class="validate-field w-full pr-4 pl-4 py-3 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-xs shadow-inner transition-all">
                            </div>
                            <div class="relative custom-tooltip" data-error="">
                                <input type="text" id="reg-phone" name="phone" value="{{ old('phone') }}" placeholder="Tél (ex: +33...)" class="w-full pr-4 pl-4 py-3 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-xs shadow-inner transition-all">
                            </div>
                        </div>

                        <div class="relative flex items-center group custom-tooltip" data-error="">
                            <input type="email" id="reg-email" name="email" value="{{ old('email') }}" required placeholder="Email *" class="validate-field w-full pr-12 pl-4 py-3 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-xs shadow-inner transition-all">
                            <i data-lucide="mail" class="absolute right-4 w-4 h-4 text-gray-400"></i>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative flex items-center custom-tooltip" data-error="">
                                <input type="password" id="reg-pass" name="password" required placeholder="MDP (6+ car.) *" class="validate-field w-full pr-8 pl-4 py-3 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-xs shadow-inner transition-all">
                                <button type="button" onclick="togglePass('reg-pass', 'eye-reg')" class="absolute right-2.5"><i id="eye-reg" data-lucide="eye" class="w-3.5 h-3.5 text-gray-400"></i></button>
                            </div>
                            <div class="relative flex items-center custom-tooltip" data-error="">
                                <input type="password" id="reg-pass-confirm" name="password_confirmation" required placeholder="Confirmer *" class="validate-field w-full pr-4 pl-4 py-3 bg-gray-100/60 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-xs shadow-inner transition-all">
                            </div>
                        </div>

                        <button type="submit" id="reg-submit-btn" class="w-full bg-orange-500 text-white font-black py-3.5 rounded-2xl hover:bg-orange-600 transition-all uppercase tracking-widest text-[10px] mt-2 shadow-lg shadow-orange-500/10">
                            Créer mon compte
                        </button>
                    </form>

                    <!-- Switch Mobile -->
                    <div class="mt-5 pt-4 border-t border-gray-100 text-center md:hidden">
                        <button onclick="movePanel('right')" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-orange-50 text-orange-600 font-bold text-[11px] tracking-wide active:scale-95 transition-all">
                            <i data-lucide="arrow-left" class="w-3 h-3"></i> Se connecter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- OVERLAY DESKTOP -->
        <div id="overlay-panel" class="hidden md:flex absolute top-0 left-1/2 w-1/2 h-full bg-orange-500 z-20 flex-col justify-center items-center text-white px-8 text-center shadow-2xl overflow-hidden">
            <div class="paper-texture"></div>
            <div class="paper-line-crack"></div>
            
            <div id="overlay-to-reg" class="transition-all duration-300 relative z-10">
                <div class="p-2.5 bg-white/20 rounded-full mb-3 inline-block">
                    <i data-lucide="chef-hat" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-2xl font-black uppercase mb-1 tracking-tight">Oura<span>Table</span></h3>
                <p class="text-[11px] mb-6 opacity-85 font-medium italic">Pas encore de compte ?</p>
                <button onclick="movePanel('left')" class="border-2 border-white px-8 py-2.5 rounded-full font-bold uppercase text-[10px] tracking-widest hover:bg-white hover:text-orange-500 transition-all shadow-md">
                    S'inscrire
                </button>
            </div>

            <div id="overlay-to-log" class="hidden transition-all duration-300 relative z-10">
                <div class="p-2.5 bg-white/20 rounded-full mb-3 inline-block text-center">
                    <i data-lucide="utensils-crossed" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-2xl font-black uppercase mb-1 tracking-tight">Bienvenue !</h3>
                <p class="text-[11px] mb-6 opacity-85 font-medium italic">Déjà membre ?</p>
                <button onclick="movePanel('right')" class="border-2 border-white px-8 py-2.5 rounded-full font-bold uppercase text-[10px] tracking-widest hover:bg-white hover:text-orange-500 transition-all shadow-md">
                    Se connecter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE MESSAGE UNIQUE -->
<div id="customModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay"></div>
    <div class="custom-modal-container">
        <div class="custom-modal-content" id="modalContent">
            <div class="custom-modal-icon" id="modalIcon">
                <i data-lucide="alert-circle"></i>
            </div>
            <h3 class="custom-modal-title" id="modalTitle">Titre</h3>
            <p class="custom-modal-message" id="modalMessage">Message</p>
            <div class="custom-modal-buttons">
                <button class="custom-modal-btn" id="modalCloseBtn" onclick="closeModal()">Compris</button>
            </div>
        </div>
    </div>
</div>

<script>
// Forcer la régénération du token CSRF au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour tous les tokens CSRF cachés
    const token = '{{ csrf_token() }}';
    document.querySelectorAll('input[name="_token"]').forEach(input => {
        input.value = token;
    });
    
    // Afficher le modal de succès d'inscription
    @if(session('register_success'))
        setTimeout(function() {
            showModal('success', '{{ session('modal_title') }}', '{{ session('modal_message') }}', 'check-circle');
        }, 500);
    @endif
    
    // Afficher le modal de bienvenue (connexion réussie) puis rediriger
    @if(session('welcome_back'))
        setTimeout(function() {
            showModal('success', 'Bon retour à table !', '{{ session('welcome_back') }}', 'chef-hat');
        }, 300);
        @if(session('redirect_to'))
        setTimeout(function() {
            window.location.href = '{{ session('redirect_to') }}';
        }, 2200);
        @endif
    @endif
    
    // Afficher le modal d'email non vérifié
    @if(session('email_not_verified'))
        setTimeout(function() {
            showModal('warning', 'Compte non vérifié', '{{ session('email_not_verified') }}', 'mail-check');
        }, 500);
    @endif
    
    // Afficher les erreurs du formulaire d'inscription
    @if($errors->has('email_exists'))
        setTimeout(function() {
            showModal('warning', 'Email déjà existant', 'Cet email est déjà utilisé. Connectez-vous ou utilisez une autre adresse.', 'mail-check');
        }, 500);
    @endif
    
    @if($errors->has('phone_exists'))
        setTimeout(function() {
            showModal('warning', 'Numéro déjà utilisé', 'Ce numéro de téléphone existe déjà dans notre système.', 'phone');
        }, 500);
    @endif
    
    // Afficher les erreurs du formulaire de connexion
    @if($errors->has('email_missing'))
        setTimeout(function() {
            showModal('error', 'Email introuvable', 'Cet email n\'existe pas dans notre système. Vérifiez votre saisie ou créez un compte.', 'mail-x');
        }, 500);
    @endif
    
    @if($errors->has('password_incorrect'))
        setTimeout(function() {
            showModal('error', 'Mot de passe incorrect', 'Le mot de passe que vous avez saisi est incorrect. Veuillez réessayer.', 'lock-keyhole');
        }, 500);
    @endif
});

lucide.createIcons();

// GESTION DES MODALS UNIQUES
let currentModal = null;

function showModal(type, title, message, icon = 'alert-circle') {
    // Fermer le modal existant
    if (currentModal) {
        closeModal();
    }
    
    const modal = document.getElementById('customModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    
    let iconColor = '';
    let iconBg = '';
    let titleColor = '';
    
    switch(type) {
        case 'success':
            iconColor = 'text-green-500';
            iconBg = 'bg-green-100';
            titleColor = 'text-green-700';
            break;
        case 'error':
            iconColor = 'text-red-500';
            iconBg = 'bg-red-100';
            titleColor = 'text-red-700';
            break;
        case 'warning':
            iconColor = 'text-orange-500';
            iconBg = 'bg-orange-100';
            titleColor = 'text-orange-700';
            break;
        case 'info':
            iconColor = 'text-blue-500';
            iconBg = 'bg-blue-100';
            titleColor = 'text-blue-700';
            break;
        default:
            iconColor = 'text-orange-500';
            iconBg = 'bg-orange-100';
            titleColor = 'text-gray-800';
    }
    
    modalIcon.className = `custom-modal-icon ${iconBg}`;
    const iconElement = modalIcon.querySelector('i');
    if (iconElement) {
        iconElement.setAttribute('data-lucide', icon);
        iconElement.className = iconColor;
    }
    modalTitle.className = `custom-modal-title ${titleColor}`;
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    
    modal.style.display = 'flex';
    currentModal = modal;
    
    setTimeout(() => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }, 100);
    
    // Auto-fermeture après 5 secondes pour les succès
    if (type === 'success') {
        setTimeout(() => {
            closeModal();
        }, 5000);
    }
}

function closeModal() {
    if (currentModal) {
        currentModal.style.display = 'none';
        currentModal = null;
    }
}

// Sécurisation du focus
function safeFocus(element) {
    if (element && element !== document.activeElement && typeof element.focus === 'function') {
        try {
            element.focus();
        } catch(e) {
            console.warn('Focus error:', e);
        }
    }
}

function showInlineError(inputId, message) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    const container = input.closest('.custom-tooltip');
    if (container) {
        container.setAttribute('data-error', message);
        container.classList.add('has-error');
        input.classList.add('border-red-500');
        
        setTimeout(() => {
            if (container) {
                container.classList.remove('has-error');
                input.classList.remove('border-red-500');
            }
        }, 3000);
    }
}

// Validation des champs
document.addEventListener("DOMContentLoaded", function() {
    const nameInput = document.getElementById('reg-name');
    const phoneInput = document.getElementById('reg-phone');
    const passInput = document.getElementById('reg-pass');
    const confirmInput = document.getElementById('reg-pass-confirm');
    const allRequiredInputs = document.querySelectorAll('.validate-field');

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (nameInput) {
        nameInput.addEventListener('input', function() {
            let cleanValue = this.value.replace(/[^a-zA-ZÀ-ÿ\s-]/g, '');
            this.value = cleanValue.replace(/(^\w|\s\w|-\w)/g, function(match) {
                return match.toUpperCase();
            });
            checkFieldStatus(this);
        });
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value;
            if (value.length > 0) {
                if (!value.startsWith('+')) value = '+' + value;
                const plus = value.charAt(0);
                const rest = value.slice(1).replace(/\D/g, '');
                this.value = plus + rest;
            }
            checkFieldStatus(this);
        });
    }

    function checkFieldStatus(input) {
        if (!input) return;
        
        const container = input.closest('.custom-tooltip');
        if (!container) return;

        const val = input.value.trim();
        let errorMsg = "";

        if (val === "" && input.hasAttribute('required')) {
            errorMsg = "Champ obligatoire";
        } else if (input.type === "email" && val !== "" && !emailRegex.test(val)) {
            errorMsg = "Email incorrect";
        } else if (input.id === 'reg-pass' && val !== "" && val.length < 6) {
            errorMsg = "Min. 6 caractères requis";
        } else if ((input.id === 'reg-pass' || input.id === 'reg-pass-confirm') && passInput && confirmInput && passInput.value !== confirmInput.value && confirmInput.value.length > 0) {
            errorMsg = "Les mots de passe ne correspondent pas";
        } else if (input.id === 'reg-phone' && val !== "" && val.length > 0 && val.length < 10) {
            errorMsg = "Numéro incomplet";
        }

        if (errorMsg !== "") {
            input.classList.remove('border-transparent', 'border-green-500');
            input.classList.add('border-red-500');
            container.setAttribute('data-error', errorMsg);
            container.classList.add('has-error');
        } else {
            input.classList.remove('border-red-500', 'border-transparent');
            if (input.id === 'reg-pass' || input.id === 'reg-pass-confirm') {
                if (passInput && confirmInput && passInput.value === confirmInput.value && passInput.value.length >= 6) {
                    passInput.classList.remove('border-red-500');
                    passInput.classList.add('border-green-500');
                    confirmInput.classList.remove('border-red-500');
                    confirmInput.classList.add('border-green-500');
                    const passContainer = document.getElementById('reg-pass')?.closest('.custom-tooltip');
                    const confirmContainer = document.getElementById('reg-pass-confirm')?.closest('.custom-tooltip');
                    if (passContainer) passContainer.classList.remove('has-error');
                    if (confirmContainer) confirmContainer.classList.remove('has-error');
                }
            } else if (val !== "" && input.id !== 'reg-phone') {
                input.classList.add('border-green-500');
                container.classList.remove('has-error');
            } else if (input.id === 'reg-phone' && val !== "") {
                input.classList.add('border-green-500');
                container.classList.remove('has-error');
            }
        }
    }

    allRequiredInputs.forEach(input => {
        if (input) {
            input.addEventListener('blur', () => checkFieldStatus(input));
            input.addEventListener('input', () => {
                if(input.classList.contains('border-red-500') || input.id === 'reg-pass-confirm' || input.id === 'reg-pass') {
                    checkFieldStatus(input);
                }
            });
        }
    });
    
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const emailInput = document.getElementById('login-email');
            const passInput = document.getElementById('login-pass');
            if (emailInput && !emailInput.value.trim()) {
                e.preventDefault();
                showInlineError('login-email', 'Email requis');
                safeFocus(emailInput);
            } else if (passInput && !passInput.value.trim()) {
                e.preventDefault();
                showInlineError('login-pass', 'Mot de passe requis');
                safeFocus(passInput);
            }
        });
    }
    
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const nameField = document.getElementById('reg-name');
            const emailField = document.getElementById('reg-email');
            const passField = document.getElementById('reg-pass');
            const confirmField = document.getElementById('reg-pass-confirm');
            const phoneField = document.getElementById('reg-phone');
            
            if (nameField && !nameField.value.trim()) {
                e.preventDefault();
                showInlineError('reg-name', 'Nom requis');
                safeFocus(nameField);
                return;
            }
            if (emailField && !emailField.value.trim()) {
                e.preventDefault();
                showInlineError('reg-email', 'Email requis');
                safeFocus(emailField);
                return;
            }
            if (emailField && !emailRegex.test(emailField.value.trim())) {
                e.preventDefault();
                showInlineError('reg-email', 'Email invalide');
                safeFocus(emailField);
                return;
            }
            if (passField && !passField.value.trim()) {
                e.preventDefault();
                showInlineError('reg-pass', 'Mot de passe requis');
                safeFocus(passField);
                return;
            }
            if (passField && passField.value.trim().length < 6) {
                e.preventDefault();
                showInlineError('reg-pass', 'Minimum 6 caractères');
                safeFocus(passField);
                return;
            }
            if (confirmField && confirmField.value.trim() !== passField.value.trim()) {
                e.preventDefault();
                showInlineError('reg-pass-confirm', 'Les mots de passe ne correspondent pas');
                safeFocus(confirmField);
                return;
            }
            if (phoneField && phoneField.value.trim() !== "" && phoneField.value.trim().length < 10) {
                e.preventDefault();
                showInlineError('reg-phone', 'Numéro de téléphone incomplet');
                safeFocus(phoneField);
                return;
            }
        });
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList && e.target.classList.contains('custom-modal-overlay')) {
        closeModal();
    }
});

function movePanel(direction) {
    const overlay = document.getElementById('overlay-panel');
    const toReg = document.getElementById('overlay-to-reg');
    const toLog = document.getElementById('overlay-to-log');
    const loginSec = document.getElementById('login-section');
    const regSec = document.getElementById('register-section');

    if (window.innerWidth < 768) {
        if (direction === 'left') {
            if (loginSec) {
                loginSec.classList.add('mobile-slide-out');
                setTimeout(() => {
                    loginSec.classList.add('hidden');
                    loginSec.classList.remove('mobile-slide-out');
                    if (regSec) {
                        regSec.classList.remove('hidden');
                        regSec.classList.add('mobile-slide-in');
                        setTimeout(() => regSec.classList.remove('mobile-slide-in'), 400);
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                }, 250);
            }
        } else {
            if (regSec) {
                regSec.classList.add('mobile-slide-out');
                setTimeout(() => {
                    regSec.classList.add('hidden');
                    regSec.classList.remove('mobile-slide-out');
                    if (loginSec) {
                        loginSec.classList.remove('hidden');
                        loginSec.classList.add('mobile-slide-in');
                        setTimeout(() => loginSec.classList.remove('mobile-slide-in'), 400);
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                }, 250);
            }
        }
        return; 
    }

    if (!overlay) return;
    
    overlay.classList.add('shatter-active');
    setTimeout(() => {
        if (direction === 'left') {
            overlay.style.left = '0%';
            if (toReg) toReg.classList.add('hidden');
            if (toLog) toLog.classList.remove('hidden');
        } else {
            overlay.style.left = '50%';
            if (toLog) {
                toLog.classList.add('hidden');
            }
            if (toReg) toReg.classList.remove('hidden');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }, 400); 
    setTimeout(() => { overlay.classList.remove('shatter-active'); }, 850);
}

function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    
    input.type = input.type === "password" ? "text" : "password";
    icon.setAttribute('data-lucide', input.type === "password" ? 'eye' : 'eye-off');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function checkHash() {
    const hash = window.location.hash;
    if (hash === '#register') movePanel('left');
    else if (hash === '#login') movePanel('right');
}

document.addEventListener("DOMContentLoaded", function() {
    checkHash();
    window.addEventListener('hashchange', checkHash);

    @if(old('name') || $errors->has('phone') || $errors->has('name') || (old('name') && $errors->has('email')))
        movePanel('left');
    @endif
    
    @if(!old('name') && ($errors->has('email_missing') || $errors->has('password_incorrect') || session('welcome') || session('welcome_back')))
        movePanel('right');
    @endif
});
</script>

<style>
    html, body { overflow: hidden; height: 100%; max-height: 100vh; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }

    .custom-tooltip { position: relative; }
    .custom-tooltip::after {
        content: attr(data-error);
        position: absolute;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%) translateY(5px);
        background-color: #1f2937;
        color: white;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.2s ease-in-out;
        z-index: 50;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .custom-tooltip::before {
        content: '';
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%) translateY(5px);
        border-width: 5px;
        border-style: solid;
        border-color: #1f2937 transparent transparent transparent;
        opacity: 0;
        pointer-events: none;
        transition: all 0.2s ease-in-out;
        z-index: 50;
    }
    .custom-tooltip.has-error:hover::after,
    .custom-tooltip.has-error:hover::before {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(20px, -20px) scale(1.04); }
        66% { transform: translate(-10px, 10px) scale(0.96); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 12s infinite ease-in-out; }

    .mobile-slide-in { animation: mobileSlideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    .mobile-slide-out { animation: mobileSlideOut 0.25s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    
    @keyframes mobileSlideIn {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes mobileSlideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-40px); }
    }

    .shatter-active { animation: pptShatter 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    @keyframes pptShatter {
        0% { clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%); transform: scale(1) translateY(0) rotate(0deg); opacity: 1; }
        18% { clip-path: polygon(0% 0%, 55% 8%, 45% 0%, 100% 0%, 94% 45%, 100% 55%, 100% 100%, 38% 94%, 32% 100%, 0% 100%, 5% 48%); transform: scale(0.98) rotate(0.5deg); }
        45%, 55% { clip-path: polygon(10% 20%, 38% 35%, 50% 15%, 90% 30%, 75% 60%, 90% 90%, 55% 82%, 35% 70%, 10% 85%, 20% 48%); transform: scale(0.85) translateY(400px) rotate(8deg); opacity: 0; }
        56% { clip-path: polygon(10% 20%, 38% 35%, 50% 15%, 90% 30%, 75% 60%, 90% 90%, 55% 82%, 35% 70%, 10% 85%, 20% 48%); transform: scale(0.85) translateY(-400px) rotate(-8deg); opacity: 0; }
        100% { clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%); transform: scale(1) translateY(0) rotate(0deg); opacity: 1; }
    }

    input::placeholder { color: #9ca3af; }
    .paper-texture {
        position: absolute; inset: 0; opacity: 0.04; mix-blend-mode: overlay; pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    }
    .paper-line-crack {
        position: absolute; inset: 0; opacity: 0.08; pointer-events: none;
        background-image: linear-gradient(135deg, transparent 47%, #000 48%, #fff 50%, transparent 51%), linear-gradient(45deg, transparent 74%, #000 75%, #fff 76%, transparent 78%);
        background-size: 250px 250px;
    }
    
    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: modalFadeIn 0.3s ease;
    }
    
    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .custom-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }
    
    .custom-modal-container {
        position: relative;
        z-index: 10001;
        max-width: 400px;
        width: 90%;
        padding: 20px;
    }
    
    .custom-modal-content {
        background: white;
        border-radius: 28px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalPop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .custom-modal-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 20px;
        border-radius: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .custom-modal-icon i {
        width: 40px;
        height: 40px;
    }
    
    .custom-modal-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 12px;
        letter-spacing: -0.3px;
    }
    
    .custom-modal-message {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 28px;
    }
    
    .custom-modal-buttons {
        display: flex;
        justify-content: center;
    }
    
    .custom-modal-btn {
        background: #f97316;
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 60px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }
    
    .custom-modal-btn:hover {
        background: #ea580c;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(249, 115, 22, 0.4);
    }
    
    .bg-green-100 { background-color: #dcfce7; }
    .bg-red-100 { background-color: #fee2e2; }
    .bg-orange-100 { background-color: #ffedd5; }
    .bg-blue-100 { background-color: #dbeafe; }
    
    .text-green-500 { color: #22c55e; }
    .text-red-500 { color: #ef4444; }
    .text-orange-500 { color: #f97316; }
    .text-blue-500 { color: #3b82f6; }
    .text-green-700 { color: #15803d; }
    .text-red-700 { color: #b91c1c; }
    .text-orange-700 { color: #c2410c; }
</style>
@endsection