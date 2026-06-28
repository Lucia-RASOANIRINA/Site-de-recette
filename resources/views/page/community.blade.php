@extends('layouts.header')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    /* Animations */
    @keyframes floatIn {
        0% {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            filter: blur(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    }

    @keyframes modalPop {
        0% {
            opacity: 0;
            transform: scale(0.8) rotate(-5deg);
        }
        50% {
            transform: scale(1.05) rotate(2deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0);
        }
    }

    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.2); }
        50% { transform: scale(0.95); }
        75% { transform: scale(1.1); }
    }

    .animate-float-in {
        animation: floatIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-modal-pop {
        animation: modalPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .animate-heart-beat {
        animation: heartBeat 0.6s ease-in-out;
    }

    /* Layout principal */
    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Hero Section */
    .hero-section {
        background: #fff9f0;
        border-radius: 2rem;
        margin-bottom: 32px;
        padding: 48px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        border: 1px solid #fde68a;
    }

    .hero-section::after {
        content: '🍽️';
        position: absolute;
        bottom: 0;
        right: 0;
        font-size: 180px;
        opacity: 0.05;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .hero-icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f97316;
        border-radius: 2rem;
        padding: 16px;
        margin-bottom: 24px;
        box-shadow: 0 12px 24px -8px rgba(249, 115, 22, 0.4);
    }

    .hero-icon-wrapper i {
        width: 48px;
        height: 48px;
        color: white;
    }

    .hero-title-main {
        font-size: 3.5rem;
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 16px;
        letter-spacing: -0.02em;
    }

    .hero-title-main span {
        color: #f97316;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: #6b7280;
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .hero-stats-inline {
        display: flex;
        gap: 48px;
        margin-top: 32px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .hero-stat {
        text-align: center;
        transition: all 0.3s ease;
        padding: 12px 24px;
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        min-width: 120px;
        border: 1px solid #fde68a;
    }

    .hero-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px -8px rgba(249, 115, 22, 0.3);
        background: #fef3c7;
        border-color: #f97316;
    }

    .hero-stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #f97316;
        line-height: 1;
    }

    .hero-stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .hero-title-main { font-size: 2rem; }
        .hero-section { padding: 32px 20px; }
        .hero-stats-inline { gap: 16px; }
        .hero-stat { padding: 8px 16px; min-width: 90px; }
        .hero-stat-number { font-size: 1.8rem; }
    }

    /* IA Challenge card */
    .ia-challenge-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        transition: all 0.3s ease;
        padding: 24px;
        margin-bottom: 32px;
    }
    .ia-challenge-card:hover {
        border-color: #f97316;
        box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Image container */
    .image-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 2rem;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
    }

    .image-frame {
        position: absolute;
        inset: -2px;
        border: 3px solid white;
        border-radius: 2.2rem;
        z-index: 20;
        pointer-events: none;
    }

    .image-backdrop {
        position: absolute;
        inset: 0;
        background: #f97316;
        border-radius: 2rem;
        transform: rotate(2deg) scale(1.05);
        opacity: 0.2;
        transition: all 0.6s ease;
        filter: blur(5px);
    }

    .image-main {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 1.8rem;
        transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 10;
    }

    .image-container:hover .image-main { transform: scale(1.08); }
    .image-container:hover .image-backdrop { transform: rotate(4deg) scale(1.15); opacity: 0.3; filter: blur(8px); }

    /* Cartes post */
    .post-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .post-card:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
        border-color: #f97316;
    }

    .interaction-btn {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 8px 16px;
        border-radius: 2rem;
        font-size: 13px;
        cursor: pointer;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .interaction-btn:hover { 
        background: #fef3c7; 
        border-color: #f97316; 
        transform: scale(1.05);
    }

    /* Badges */
    .badge-question { background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 2rem; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-realisation { background: #fef3c7; color: #f97316; padding: 4px 12px; border-radius: 2rem; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-defi { background: #f3e8ff; color: #7e22ce; padding: 4px 12px; border-radius: 2rem; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }

    /* Scroll personnalisé */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f97316; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ea580c; }

    /* Last comment card */
    .last-comment-card {
        background: #fefce8;
        border: 1px solid #fde68a;
        border-radius: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .last-comment-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #f97316;
    }
    .last-comment-card:hover { border-color: #f97316; transform: translateX(5px); }

    /* Filter buttons */
    .filter-btn {
        padding: 10px 24px;
        border-radius: 2rem;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .filter-btn.active { 
        background: #f97316; 
        color: white; 
        border-color: #f97316; 
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
    }
    .filter-btn:hover:not(.active) { border-color: #f97316; color: #f97316; transform: translateY(-2px); }

    /* Buttons */
    .btn-orange { 
        background: #f97316;
        color: white; 
        padding: 12px 28px; 
        border-radius: 2rem; 
        font-size: 14px; 
        font-weight: 600; 
        border: none; 
        cursor: pointer; 
        transition: all 0.2s; 
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-orange:hover { 
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
        background: #ea580c;
    }

    /* Login Prompt Card */
    .login-prompt-card {
        background: #fff9f0;
        border: 2px dashed #f97316;
        border-radius: 1.5rem;
        transition: all 0.3s ease;
        padding: 40px 32px;
        margin-top: 32px;
        margin-bottom: 32px;
        text-align: center;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    .login-prompt-card:hover {
        background: #fef3c7;
        transform: translateY(-4px);
        box-shadow: 0 12px 28px -8px rgba(249, 115, 22, 0.2);
    }

    /* Stats Modal */
    .stats-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        z-index: 1500;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stats-modal-content {
        background: white;
        border-radius: 2rem;
        max-width: 450px;
        width: 90%;
        animation: modalPop 0.3s ease;
        overflow: hidden;
    }
    .stats-modal-header {
        background: #f97316;
        padding: 24px;
        text-align: center;
        color: white;
    }
    .stats-modal-header i {
        font-size: 48px;
        margin-bottom: 12px;
    }
    .stats-modal-header h2 {
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }
    .stats-modal-body {
        padding: 28px;
        text-align: center;
    }
    .stats-modal-number {
        font-size: 72px;
        font-weight: bold;
        color: #f97316;
        margin: 16px 0;
    }
    .stats-modal-body p {
        color: #6b7280;
        margin-bottom: 24px;
    }
    .stats-modal-close {
        background: #f97316;
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 2rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .stats-modal-close:hover {
        background: #ea580c;
        transform: scale(1.02);
    }

    /* Modal overlay */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: white;
        border-radius: 1.5rem;
        max-width: 500px;
        width: 90%;
        animation: modalPop 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        padding: 24px;
        text-align: center;
    }

    /* Message box style */
    .message-box {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 40px -15px rgba(0, 0, 0, 0.2);
        border: 1px solid #f97316;
        position: relative;
        overflow: hidden;
    }

    /* Wave effect */
    .wave-effect {
        position: relative;
        overflow: hidden;
    }
    .wave-effect::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }
    .wave-effect:active::after {
        width: 300px;
        height: 300px;
    }

    /* Toast notification */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #f97316;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        box-shadow: 0 10px 40px rgba(249, 115, 22, 0.4);
        z-index: 2000;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: bold;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        font-size: 14px;
    }
    .toast-notification.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    /* Message floating button */
    .message-floating-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        background: #f97316;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.35);
        transition: all 0.3s ease;
        z-index: 1100;
    }
    .message-floating-btn:hover {
        transform: scale(1.08);
        background: #ea580c;
    }

    /* Groups list inside modal */
    .groups-list {
        max-height: 300px;
        overflow-y: auto;
        margin: 16px 0;
    }
    .group-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }
    .group-item:hover {
        background: #fef3c7;
    }
    .group-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .group-avatar {
        width: 40px;
        height: 40px;
        background: #f97316;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }
    .group-name {
        font-weight: 600;
        color: #1f2937;
    }
    .group-members {
        font-size: 11px;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }
    .group-join-btn {
        background: none;
        border: 1px solid #f97316;
        color: #f97316;
        padding: 6px 16px;
        border-radius: 2rem;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .group-join-btn:hover {
        background: #f97316;
        color: white;
    }

    .animation-delay-1000 { animation-delay: 1s; }
    .animation-delay-2000 { animation-delay: 2s; }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen bg-gray-50 pb-20"
     x-data="{ 
        activeFilter: 'all',
        showLoginModal: false,
        showGroupsModal: false,
        selectedAction: '',
        showComments: false,
        hoveredPost: null,
        groups: @json($groups ?? [])
     }">
    
    {{-- MODALE PRINCIPALE (pour like/share/comment/message) --}}
    <div x-show="showLoginModal" 
         x-cloak
         @keydown.escape.window="showLoginModal = false"
         class="fixed inset-0 z-[2000] flex items-center justify-center p-4 bg-black/50 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showLoginModal = false" 
             class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden animate-modal-pop border border-orange-100 message-box">
            
            <div class="h-2 bg-orange-500"></div>
            
            <div class="px-8 pt-8 pb-6 text-center">
                <div class="relative mx-auto mb-6 w-20 h-20">
                    <div class="absolute inset-0 bg-orange-200 rounded-2xl rotate-45 animate-pulse"></div>
                    <div class="absolute inset-0 bg-orange-500 rounded-2xl rotate-12 flex items-center justify-center">
                        <div x-show="selectedAction === 'like'" x-cloak>
                            <i data-lucide="heart" class="w-10 h-10 text-white animate-heart-beat" stroke-width="1.5"></i>
                        </div>
                        <div x-show="selectedAction === 'share'" x-cloak>
                            <i data-lucide="share-2" class="w-10 h-10 text-white" stroke-width="1.5"></i>
                        </div>
                        <div x-show="selectedAction === 'comment'" x-cloak>
                            <i data-lucide="message-circle" class="w-10 h-10 text-white" stroke-width="1.5"></i>
                        </div>
                        <div x-show="selectedAction === 'message'" x-cloak>
                            <i data-lucide="mail" class="w-10 h-10 text-white" stroke-width="1.5"></i>
                        </div>
                        <div x-show="selectedAction === 'defi'" x-cloak>
                            <i data-lucide="trophy" class="w-10 h-10 text-white" stroke-width="1.5"></i>
                        </div>
                        <div x-show="selectedAction === 'join'" x-cloak>
                            <i data-lucide="users" class="w-10 h-10 text-white" stroke-width="1.5"></i>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                    <span x-text="selectedAction === 'like' ? 'Un like qui compte' : (selectedAction === 'share' ? 'Partagez avec vos amis' : (selectedAction === 'comment' ? 'Rejoignez la conversation' : (selectedAction === 'message' ? 'Envoyez un message' : (selectedAction === 'defi' ? 'Participez au défi' : 'Rejoignez un groupe'))))"></span>
                </h3>
                
                <p class="text-gray-500 mb-8">
                    Rejoignez les <span class="font-bold text-orange-500">{{ $totalMembres }}</span> membres de la communauté
                </p>
            </div>
            
            <div class="px-8 pb-8 space-y-3">
                <a href="{{ route('login') }}#login" 
                   class="group relative block w-full py-4 bg-orange-500 text-white font-bold rounded-xl overflow-hidden transition-all hover:shadow-xl hover:shadow-orange-500/25 hover:-translate-y-1 wave-effect text-center">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <i data-lucide="log-in" class="w-5 h-5 group-hover:rotate-12 transition-transform" stroke-width="1.5"></i>
                        Se connecter
                    </span>
                </a>
                
                <a href="{{ route('login') }}#register" 
                   class="group relative block w-full py-4 bg-gray-100 text-gray-700 font-bold rounded-xl overflow-hidden transition-all hover:bg-gray-200 hover:-translate-y-1 wave-effect text-center">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5 text-orange-500 group-hover:scale-110 transition-transform" stroke-width="1.5"></i>
                        Créer un compte
                    </span>
                </a>
                
                <button @click="showLoginModal = false" 
                        class="w-full py-4 text-sm font-medium text-gray-400 hover:text-orange-500 transition-all duration-300 ease-in-out group">
                    <span class="relative inline-block">Continuer sans rejoindre</span>
                </button>
            </div>
            
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center flex items-center justify-center gap-2">
                    <i data-lucide="users" class="w-4 h-4" stroke-width="1.5"></i>
                    +{{ $totalMembres }} membres ont déjà rejoint
                </p>
            </div>
        </div>
    </div>

    {{-- MODALE GROUPES --}}
    <div x-show="showGroupsModal" 
         x-cloak
         @keydown.escape.window="showGroupsModal = false"
         class="fixed inset-0 z-[2000] flex items-center justify-center p-4 bg-black/40 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showGroupsModal = false" 
             class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-modal-pop border border-orange-100 message-box">
            
            <div class="h-2 bg-orange-500"></div>
            
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="relative mx-auto mb-4 w-16 h-16">
                    <div class="absolute inset-0 bg-orange-200 rounded-2xl rotate-45 animate-pulse"></div>
                    <div class="absolute inset-0 bg-orange-500 rounded-2xl rotate-12 flex items-center justify-center">
                        <i data-lucide="users" class="w-8 h-8 text-white" stroke-width="1.5"></i>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-1">Groupes de discussion</h3>
                <p class="text-gray-500 text-sm">Rejoignez un groupe pour échanger avec d'autres passionnés</p>
            </div>
            
            <div class="px-6">
                <div class="groups-list custom-scrollbar">
                    <template x-for="group in groups" :key="group.id">
                        <div class="group-item">
                            <div class="group-info">
                                <div class="group-avatar" x-text="group.name.substring(0,2).toUpperCase()"></div>
                                <div>
                                    <div class="group-name" x-text="group.name"></div>
                                    <div class="group-members">
                                        <i data-lucide="users" class="w-3 h-3"></i>
                                        <span x-text="group.member_count + ' membres'"></span>
                                    </div>
                                </div>
                            </div>
                            <button class="group-join-btn" @click="showLoginModal = true; selectedAction = 'join'; showGroupsModal = false">
                                Rejoindre
                            </button>
                        </div>
                    </template>
                    <div x-show="groups.length === 0" class="text-center text-gray-400 py-8">
                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                        <p>Aucun groupe disponible</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 pb-6 pt-2">
                <button @click="showGroupsModal = false" class="w-full py-3 text-sm text-gray-400 hover:text-orange-500 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    {{-- HERO SECTION --}}
    <div class="main-container">
        <div class="hero-section animate-float-in">
            <div class="hero-content">
                <div class="hero-icon-wrapper">
                    <i data-lucide="chef-hat"></i>
                </div>
                <h1 class="hero-title-main">
                    Le Fil <span>des Gourmets</span>
                </h1>
                <div class="hero-subtitle">
                    <i data-lucide="sparkles" class="w-4 h-4 text-orange-500"></i>
                    <span>Le point de rencontre des Cœurs Gourmands</span>
                    <i data-lucide="heart" class="w-4 h-4 text-orange-500"></i>
                </div>
                <div class="hero-subtitle" style="font-size: 0.9rem; margin-top: 8px;">
                    <span>Partagez, échangez et inspirez-vous autour de la cuisine</span>
                </div>
                
                <div class="hero-stats-inline">
                    <div class="hero-stat" >
                        <div class="hero-stat-number">{{ $totalPosts }}</div>
                        <div class="hero-stat-label">Publications</div>
                    </div>
                    <div class="hero-stat" >
                        <div class="hero-stat-number">{{ $totalMembres }}</div>
                        <div class="hero-stat-label">Membres</div>
                    </div>
                    <div class="hero-stat" >
                        <div class="hero-stat-number">{{ $totalComments }}</div>
                        <div class="hero-stat-label">Commentaires</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DÉFI IA --}}
        @if(isset($aiChallenge) && $aiChallenge)
        <div class="ia-challenge-card">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="bot" class="w-6 h-6 text-orange-500"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="bg-orange-100 text-orange-600 text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <i data-lucide="zap" class="w-3 h-3"></i> DÉFI IA
                        </span>
                        <span class="bg-gray-100 text-gray-500 text-[10px] px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i> {{ $aiChallenge->duration }}j
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base mt-1">{{ $aiChallenge->title }}</h3>
                </div>
            </div>
            
            <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $aiChallenge->description }}</p>
            
            @if($aiChallenge->ingredients)
            <div class="bg-orange-50 rounded-xl p-3 mb-4">
                <p class="text-gray-700 text-xs font-bold mb-2 flex items-center gap-1">
                    <i data-lucide="package" class="w-3 h-3"></i> Ingrédients mystères :
                </p>
                <div class="flex flex-wrap gap-2">
                    @foreach($aiChallenge->ingredients as $ingredient)
                        <span class="px-2.5 py-1 bg-white rounded-full text-gray-700 text-xs flex items-center gap-1 border border-orange-200 shadow-sm">
                            <i data-lucide="chef-hat" class="w-2.5 h-2.5 text-orange-500"></i> {{ $ingredient }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
            
            <button @click="showLoginModal = true; selectedAction = 'defi'" class="w-full btn-orange flex items-center justify-center gap-2">
                <i data-lucide="award" class="w-4 h-4"></i> Participer au défi
            </button>
        </div>
        @endif

        {{-- FILTRES --}}
        <div class="flex flex-wrap gap-2 mb-6 justify-center">
            <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Tous
            </button>
            <button @click="activeFilter = 'realisation'" :class="activeFilter === 'realisation' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="cooking-pot" class="w-3.5 h-3.5"></i> Réalisations
            </button>
            <button @click="activeFilter = 'question'" :class="activeFilter === 'question' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="help-circle" class="w-3.5 h-3.5"></i> Questions
            </button>
            <button @click="activeFilter = 'defi'" :class="activeFilter === 'defi' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="trophy" class="w-3.5 h-3.5"></i> Défis
            </button>
        </div>

        {{-- LISTE DES POSTS --}}
        <div class="space-y-6">
            @forelse($posts as $post)
            @php
                $lastComment = $post->comments->last();
                $postCommentsCount = $post->comments_count ?? $post->comments->count();
            @endphp
            <div x-data="{ 
                    isHovered: false,
                    likesCount: {{ $post->likes_count ?? $post->likes->count() }},
                    showComments: false
                 }"
                 x-show="activeFilter === 'all' || activeFilter === '{{$post->type}}'"
                 x-transition:enter="transition-all duration-500 ease-out"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 @mouseenter="isHovered = true"
                 @mouseleave="isHovered = false"
                 class="post-card bg-white rounded-3xl border border-gray-100 overflow-hidden"
                 :class="{ 'shadow-xl': isHovered }">
                
                <div class="relative h-1 bg-orange-500"
                     :style="{ width: isHovered ? '100%' : '0%' }"
                     style="transition: width 0.6s ease"></div>
                
                <div class="p-6 md:p-8">
                    {{-- EN-TÊTE UTILISATEUR --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="relative w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $post->user->name }}</h4>
                                    <i data-lucide="badge-check" class="w-4 h-4 text-orange-500"></i>
                                    {{-- Bouton message à côté du nom --}}
                                    <button @click="showLoginModal = true; selectedAction = 'message'" class="text-orange-400 hover:text-orange-600 transition-colors" title="Envoyer un message">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- BADGE TYPE --}}
                        <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2
                            {{ $post->type == 'question' ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ $post->type == 'realisation' ? 'bg-orange-50 text-orange-600' : '' }}
                            {{ $post->type == 'defi' ? 'bg-purple-50 text-purple-600' : '' }}">
                            @if($post->type == 'question') 
                                <i data-lucide="help-circle" class="w-4 h-4"></i> Question
                            @elseif($post->type == 'realisation') 
                                <i data-lucide="cooking-pot" class="w-4 h-4"></i> Réalisation
                            @else 
                                <i data-lucide="trophy" class="w-4 h-4"></i> Défi
                            @endif
                        </span>
                    </div>

                    {{-- CONTENU --}}
                    <div class="mb-6 flex gap-3">
                        <i data-lucide="message-square" class="w-5 h-5 text-orange-400 flex-shrink-0 mt-1"></i>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $post->content }}</p>
                    </div>

                    {{-- IMAGE --}}
                    @if($post->image_path)
                    <div class="mb-6 flex justify-center">
                        <div class="image-container">
                            <div class="image-backdrop"></div>
                            <div class="image-frame"></div>
                            <img src="{{ asset('storage/'.$post->image_path) }}" class="image-main" alt="Post image">
                        </div>
                    </div>
                    @endif

                    {{-- BARRE D'INTERACTIONS --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button @click="showLoginModal = true; selectedAction = 'like'" class="interaction-btn">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                            <span class="font-bold text-sm" x-text="likesCount"></span>
                        </button>

                        <button @click="showComments = !showComments" class="interaction-btn">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span class="font-bold text-sm">{{ $postCommentsCount }}</span>
                        </button>

                        <button @click="showLoginModal = true; selectedAction = 'share'" class="interaction-btn ml-auto">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                        </button>
                    </div>

                    {{-- COMMENTAIRES (lecture seule) --}}
                    <div x-show="showComments" x-collapse class="mt-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div id="comments-list-{{ $post->id }}" class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                @forelse($post->comments as $comment)
                                <div class="bg-white rounded-lg p-2.5 shadow-sm">
                                    <div class="flex items-start gap-2">
                                        <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 font-bold text-xs flex-shrink-0">{{ substr($comment->user->name, 0, 1) }}</div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                                <span class="font-semibold text-gray-800 text-xs">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-600 text-sm">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-400 text-center text-sm py-3">Aucun commentaire</p>
                                @endforelse
                            </div>
                            
                            {{-- Invitation à commenter --}}
                            <div class="mt-3 p-3 bg-orange-50 rounded-xl text-center">
                                <p class="text-sm text-gray-600 mb-2 flex items-center justify-center gap-2">
                                    <i data-lucide="message-circle" class="w-4 h-4 text-orange-500"></i>
                                    Envie de réagir ?
                                </p>
                                <button @click="showLoginModal = true; selectedAction = 'comment'" class="bg-orange-500 text-white px-4 py-2 rounded-full text-sm hover:bg-orange-600 transition flex items-center justify-center gap-2 mx-auto">
                                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Connectez-vous pour commenter
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- DERNIER COMMENTAIRE --}}
                    @if($lastComment)
                    <div class="mt-4 last-comment-card p-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-xl flex items-center justify-center text-xs font-bold text-orange-600 flex-shrink-0">
                                {{ substr($lastComment->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-gray-700">{{ $lastComment->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $lastComment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $lastComment->content }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="utensils" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-400 mb-2">Rien à se mettre sous la dent...</h3>
                <p class="text-gray-400">Soyez le premier à partager une création !</p>
            </div>
            @endforelse
        </div>

        {{-- INVITATION À SE CONNECTER (centrée en bas) --}}
        <div class="login-prompt-card">
            <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="users" class="w-8 h-8 text-orange-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Rejoignez la communauté !</h3>
            <p class="text-gray-500 text-sm mb-4">Partagez vos créations, échangez avec d'autres passionnés et participez aux défis.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('login') }}#login" class="btn-orange text-sm py-2 px-4">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Se connecter
                </a>
                <a href="{{ route('login') }}#register" class="bg-gray-100 text-gray-700 hover:bg-gray-200 py-2 px-4 rounded-full text-sm font-medium transition flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> S'inscrire
                </a>
            </div>
        </div>
    </div>

    {{-- BOUTON FLOTTANT MESSAGE CORRIGÉ --}}
    <div @click="showLoginModal = true; selectedAction = 'message'" 
         class="message-floating-btn">
        <i data-lucide="message-circle" class="w-7 h-7 text-white"></i>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    function initLucide() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', initLucide);
    document.addEventListener('alpine:initialized', initLucide);
    setTimeout(initLucide, 800);
</script>

@include('layouts.footer')
@endsection