@extends('layouts.UserHeader')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes floatIn {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .animate-float-in { animation: floatIn 0.6s ease forwards; }
    .animate-modal-pop { animation: modalPop 0.3s ease forwards; }
    .animate-heart { animation: heartBeat 0.5s ease; }

    /* Avatar */
    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .profile-avatar:hover { transform: scale(1.05); }

    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar-overlay {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #f97316;
        border-radius: 50%;
        padding: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        z-index: 10;
    }
    .avatar-overlay:hover { background: #ea580c; transform: scale(1.1); }

    /* MODAL AVATAR */
    .modal-avatar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .modal-avatar-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    
    .modal-avatar-content {
        background: white;
        border-radius: 2rem;
        max-width: 600px;
        width: 100%;
        max-height: 95vh;
        display: flex;
        flex-direction: column;
        animation: modalSlideIn 0.3s ease-out;
        position: relative;
        overflow: hidden;
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }
    
    .modal-avatar-overlay.active .modal-avatar-content {
        transform: scale(1);
    }
    
    .modal-avatar-content .modal-scroll-area {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        max-height: calc(95vh - 70px);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .modal-avatar-content .modal-scroll-area::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-avatar-content .modal-scroll-area::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 10px;
    }
    
    .modal-avatar-content .modal-scroll-area::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
    
    .modal-avatar-content .modal-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }
    
    .modal-avatar-content .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem 0.5rem 2rem;
        flex-shrink: 0;
        border-bottom: 1px solid #f3f4f6;
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .modal-avatar-content .modal-header-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-avatar-content .modal-header-title .brand {
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -0.05em;
        line-height: 1;
        display: flex;
        align-items: center;
        gap: 0;
    }
    
    .modal-avatar-content .modal-header-title .brand .oura {
        color: #1f2937;
    }
    
    .modal-avatar-content .modal-header-title .brand .table {
        color: #f97316;
    }
    
    .modal-avatar-content .modal-header-title .subtitle {
        font-size: 0.6rem;
        font-weight: 600;
        color: #9ca3af;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        margin-left: 0.5rem;
    }
    
    .modal-avatar-content .modal-close {
        background: #f3f4f6;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .modal-avatar-content .modal-close:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }
    
    .modal-avatar-content .modal-close svg {
        width: 20px;
        height: 20px;
    }
    
    .avatar-modal-image {
        width: 100%;
        max-width: 400px;
        border-radius: 1.5rem;
        object-fit: contain;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .avatar-modal-actions {
        display: flex;
        gap: 1rem;
        width: 100%;
        max-width: 400px;
        margin-top: 0.5rem;
    }
    
    .avatar-modal-actions .btn-danger {
        flex: 1;
        background: #ef4444;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .avatar-modal-actions .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }
    
    .avatar-modal-actions .btn-secondary {
        flex: 1;
        background: #6b7280;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .avatar-modal-actions .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* MODAL STYLES */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        border-radius: 2rem;
        max-width: 500px;
        width: 100%;
        max-height: 95vh;
        display: flex;
        flex-direction: column;
        animation: modalSlideIn 0.3s ease-out;
        position: relative;
        overflow: hidden;
    }
    
    .modal-scroll-area {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        max-height: calc(95vh - 70px);
    }
    
    .modal-scroll-area::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-scroll-area::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 10px;
    }
    
    .modal-scroll-area::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
    
    .modal-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }
    
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem 0.5rem 2rem;
        flex-shrink: 0;
        border-bottom: 1px solid #f3f4f6;
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .modal-header-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-header-title .brand {
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -0.05em;
        line-height: 1;
        display: flex;
        align-items: center;
        gap: 0;
    }
    
    .modal-header-title .brand .oura {
        color: #1f2937;
    }
    
    .modal-header-title .brand .table {
        color: #f97316;
    }
    
    .modal-header-title .subtitle {
        font-size: 0.6rem;
        font-weight: 600;
        color: #9ca3af;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        margin-left: 0.5rem;
    }
    
    .modal-close {
        background: #f3f4f6;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .modal-close:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }
    
    .modal-close svg {
        width: 20px;
        height: 20px;
    }

    /* Cartes statistiques */
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.2rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); border-color: #f97316; }
    /* Icônes de statistiques : couleur uniforme, orange au survol */
    .stat-icon { color: #9ca3af; transition: color 0.25s ease; }
    .stat-card:hover .stat-icon { color: #f97316; }

    /* Badges */
    .badge-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
    }
    .badge-item:hover { transform: translateY(-2px); background: #ffedd5; }

    /* Barre de progression */
    .level-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .progress-bar {
        height: 8px;
        background: #f3f4f6;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: #f97316;
        border-radius: 4px;
        transition: width 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    /* Tabs */
    .tab-btn {
        padding: 10px 24px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-weight: 600;
        color: #6b7280;
        transition: all 0.2s;
        border-bottom: 2px solid transparent;
        font-size: 14px;
    }
    .tab-btn i { margin-right: 8px; }
    .tab-btn.active { color: #f97316; border-bottom-color: #f97316; background: #fff7ed; border-radius: 12px 12px 0 0; }
    .tab-btn:hover:not(.active) { color: #f97316; background: #fff7ed; border-radius: 12px 12px 0 0; }

    /* Informations - style cartes (Instagram / culinaire) */
    .info-row {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 16px 18px;
        border: 1px solid #f3f4f6;
        border-radius: 18px;
        background: #fff;
        transition: all .25s ease;
    }
    .info-row:hover {
        border-color: #fdba74;
        box-shadow: 0 12px 28px -14px rgba(249,115,22,.35);
        transform: translateY(-2px);
    }
    .info-label {
        font-weight: 800;
        color: #9ca3af;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .08em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-label svg { color: #f97316; }
    .info-value {
        color: #111827;
        font-weight: 600;
        font-size: 15px;
        padding-left: 24px;
    }

    /* Champs édition */
    .edit-field {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.2s;
        width: 100%;
        font-size: 14px;
        background: #fafafa;
    }
    .edit-field:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: white;
    }
    .edit-field.error { border-color: #ef4444; background: #fef2f2; }
    .error-message { color: #ef4444; font-size: 11px; margin-top: 4px; }

    /* Boutons */
    .btn-primary {
        background: #f97316;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary:hover { background: #ea580c; transform: translateY(-2px); }
    .btn-secondary {
        background: #6b7280;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-secondary:hover { background: #4b5563; transform: translateY(-2px); }

    /* Modal Contacter */
    .contact-modal-overlay {
        position: fixed; inset: 0; z-index: 120;
        background: rgba(17,24,39,.55); backdrop-filter: blur(6px);
        display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .contact-modal-overlay.active { display: flex; }
    .contact-modal-box {
        background: #fff; border-radius: 24px; padding: 24px;
        width: 100%; max-width: 460px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,.25);
        animation: modalPop .3s ease forwards;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid #f97316;
        color: #f97316;
        padding: 8px 20px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-outline:hover { background: #fff7ed; transform: translateY(-2px); }

    /* Cartes coup de foudre */
    .foudre-card {
        background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        border: 1px solid #fed7aa;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .foudre-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(249,115,22,0.15);
        border-color: #f97316;
    }
    .foudre-card-liked {
        background: linear-gradient(135deg, #fef3c7 0%, #fff 100%);
    }

    /* Activités */
    .activity-item {
        padding: 12px;
        border-radius: 1rem;
        transition: all 0.2s;
        background: #fafafa;
        margin-bottom: 8px;
        border: 1px solid #f3f4f6;
    }
    .activity-item:hover { background: #fff7ed; transform: translateX(5px); }

    /* Toast */
    .toast-message {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #22c55e;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        z-index: 99999;
        animation: slideInRight 0.3s ease;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }
    .toast-error { background: #ef4444; }

    .specialty-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #fff7ed;
        color: #ea580c;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid #fed7aa;
    }

    .country-select {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 14px;
        background: #fafafa;
        cursor: pointer;
        width: 100%;
        font-size: 14px;
    }
    .country-select:focus { outline: none; border-color: #f97316; background: white; }

    /* Cartes publications */
    .post-card {
        border: 1px solid #f3f4f6;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.2s;
        background: white;
    }
    .post-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        border-color: #fed7aa;
    }
    .post-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
    }

    /* Animation spin */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Scrollbar personnalisée */
    .liked-posts-grid {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .liked-posts-grid::-webkit-scrollbar {
        width: 6px;
    }
    .liked-posts-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .liked-posts-grid::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .info-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f3f4f6;
    }
    .info-actions .btn-outline {
        flex: 1;
        justify-content: center;
    }
</style>

<div class="min-h-screen bg-gray-50 py-8"
     x-data="{ 
        activeTab: 'infos',
        loading: false,
        avatarLoading: false,
        avatarDeleting: false,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        hasAvatar: {{ $user->avatar ? 'true' : 'false' }},
        avatarUrl: '{{ $user->avatar ? asset($user->avatar) : '' }}',
        showAvatarModal: false,
        formData: {
            name: '{{ $user->name }}',
            phone: '{{ $phoneData['number'] ?? '' }}',
            phone_country_code: '{{ $phoneData['country_code'] ?? '+261' }}',
            email: '{{ $user->email }}',
            bio: '{{ $user->bio ?? '' }}',
            city: '{{ $user->city ?? '' }}',
            birth_date: '{{ $user->birth_date ?? '' }}',
            specialty: '{{ $user->specialty ?? '' }}'
        },
        passwordForm: {
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        },
        errors: {},
        passwordErrors: {},
        
        formatName() {
            let name = this.formData.name;
            name = name.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
            this.formData.name = name;
        },
        
        validatePhone() {
            let phone = this.formData.phone;
            let phoneRegex = /^[0-9]{8,12}$/;
            if (phone && !phoneRegex.test(phone)) {
                this.errors.phone = 'Le numéro doit contenir 8 à 12 chiffres';
            } else {
                delete this.errors.phone;
            }
        },
        
        saveProfile() {
            if (this.errors.phone) return;
            this.loading = true;
            
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.formData)
            })
            .then(response => response.json())
            .then(data => {
                this.loading = false;
                if (data.success) {
                    closeEditModal();
                    this.showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showMessage(data.message || 'Erreur lors de la mise à jour', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.loading = false;
                this.showMessage('Erreur lors de la mise à jour', 'error');
            });
        },
        
        changePassword() {
            if (!this.passwordForm.current_password) {
                this.passwordErrors.current_password = 'Mot de passe actuel requis';
                return;
            }
            if (this.passwordForm.new_password.length < 6) {
                this.passwordErrors.new_password = 'Minimum 6 caractères';
                return;
            }
            if (this.passwordForm.new_password !== this.passwordForm.new_password_confirmation) {
                this.passwordErrors.new_password_confirmation = 'Les mots de passe ne correspondent pas';
                return;
            }
            
            this.loading = true;
            
            fetch('{{ route("profile.change-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    current_password: this.passwordForm.current_password,
                    new_password: this.passwordForm.new_password,
                    new_password_confirmation: this.passwordForm.new_password_confirmation
                })
            })
            .then(response => response.json())
            .then(data => {
                this.loading = false;
                if (data.success) {
                    closePasswordModal();
                    this.passwordForm = { current_password: '', new_password: '', new_password_confirmation: '' };
                    this.passwordErrors = {};
                    this.showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showMessage(data.message || 'Erreur lors du changement de mot de passe', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.loading = false;
                this.showMessage('Erreur lors du changement de mot de passe', 'error');
            });
        },
        
        uploadAvatar(file) {
            if (!file) return;
            this.avatarLoading = true;
            
            let formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route("profile.upload-avatar") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                this.avatarLoading = false;
                if (data.success) {
                    this.avatarUrl = data.avatar + '?t=' + Date.now();
                    this.hasAvatar = true;
                    document.querySelector('.profile-avatar').src = this.avatarUrl;
                    this.showMessage(data.message, 'success');
                    this.showAvatarModal = false;
                } else {
                    this.showMessage(data.message || 'Erreur lors de l\'upload', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.avatarLoading = false;
                this.showMessage('Erreur lors de l\'upload', 'error');
            });
        },
        
        deleteAvatar() {
            if (!confirm('Voulez-vous vraiment supprimer votre avatar ?')) return;
            
            this.avatarDeleting = true;
            fetch('{{ route("profile.delete-avatar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur serveur');
                }
                return response.json();
            })
            .then(data => {
                this.avatarDeleting = false;
                if (data.success) {
                    this.hasAvatar = false;
                    this.avatarUrl = '';
                    document.querySelector('.profile-avatar').src = 'https://ui-avatars.com/api/?background=f97316&color=fff&bold=true&size=140&name={{ urlencode($user->name) }}';
                    this.showMessage(data.message, 'success');
                    this.showAvatarModal = false;
                } else {
                    this.showMessage(data.message || 'Erreur lors de la suppression', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.avatarDeleting = false;
                this.showMessage('Erreur lors de la suppression de l\'avatar', 'error');
            });
        },
        
        showMessage(message, type) {
            this.toastMessage = message;
            this.toastType = type || 'success';
            this.showToast = true;
            setTimeout(() => { this.showToast = false; }, 3000);
        },
        
        openAvatarModal() {
            this.showAvatarModal = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeAvatarModal() {
            this.showAvatarModal = false;
            document.body.style.overflow = 'auto';
        }
     }">
    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Carte de profil principale --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-gray-100">
            <div class="relative h-44 md:h-56 bg-orange-500 overflow-hidden">
                <img src="{{ asset('storage/recettes/salade.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-40" alt="">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-600/90 via-orange-500/70 to-orange-500/40"></div>
                <div class="absolute top-5 left-6 text-white">
                    <p class="text-[10px] uppercase tracking-[0.4em] font-black opacity-80">OURATABLE</p>
                    <p class="text-xl font-black italic tracking-tight">L'art de bien manger</p>
                </div>
                <div class="absolute bottom-4 right-6 text-white/95 text-right">
                    <p class="text-sm font-semibold flex items-center gap-1 justify-end">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> {{ $user->city ?? 'Cuisine du monde' }}
                    </p>
                </div>
            </div>
            <div class="relative px-6 pb-6">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16">
                    <div class="avatar-container">
                        <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?background=f97316&color=fff&bold=true&size=140&name=' . urlencode($user->name) }}" 
                             class="profile-avatar" alt="Avatar" id="profileAvatar"
                             @click="openAvatarModal()">
                        
                        <!-- Bouton d'upload d'avatar -->
                        <div class="avatar-overlay" onclick="document.getElementById('avatarInput').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                        <form id="avatarForm" style="display: none;">
                            @csrf
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" 
                                   onchange="uploadAvatarFile(this.files[0])">
                        </form>
                    </div>
                    
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="openEditModal()" class="btn-outline text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 3l4 4-7 7H10v-4l7-7z"/>
                                <path d="M4 20h16"/>
                            </svg>
                            Modifier
                        </button>
                        <button onclick="openPasswordModal()" class="btn-outline text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Mot de passe
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cartes statistiques --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="stat-icon mx-auto mb-2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalPosts ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">Publications</div>
            </div>
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="stat-icon mx-auto mb-2">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalComments ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">Commentaires</div>
            </div>
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="stat-icon mx-auto mb-2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalLikes ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">J'aime donnés</div>
            </div>
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="stat-icon mx-auto mb-2">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalLikesReceived ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">J'aime reçus</div>
            </div>
        </div>

        {{-- Espace COUP DE FOUDRE --}}
        <div class="mb-8" id="coup-de-coeur">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Coup de foudre</h2>
                <span class="text-sm text-gray-500">- Mes coups de coeur culinaires</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Dernière recette aimée --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <h3 class="font-semibold text-gray-800">Dernier coup de foudre</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        @if(isset($lastLikedPost) && $lastLikedPost)
                        <div class="foudre-card cursor-pointer" onclick="openPostModal(@json($lastLikedPost))">
                            @if($lastLikedPost->image)
                            <img src="{{ asset($lastLikedPost->image) }}" class="post-image" alt="Recette">
                            @endif
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-orange-50 text-orange-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18M9 12h6M12 3v18"/>
                                        </svg>
                                        Recette
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                    <span class="text-xs font-medium">{{ $lastLikedPost->likes_count ?? 0 }}</span>
                                </div>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-1">{{ Str::limit($lastLikedPost->content, 80) }}</h4>
                            <p class="text-xs text-gray-500 mb-2">
                                Publié par <span class="font-medium text-orange-600">{{ $lastLikedPost->user->name ?? 'Utilisateur' }}</span> • {{ $lastLikedPost->created_at->diffForHumans() }}
                            </p>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                    {{ $lastLikedPost->comments_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <p class="text-gray-400">Vous n'avez pas encore de coup de foudre</p>
                            <p class="text-xs text-gray-400 mt-1">Aimez des recettes pour les retrouver ici !</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Liste des publications likées --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <h3 class="font-semibold text-gray-800">Mes coups de coeur</h3>
                            <span class="text-xs text-gray-400">({{ isset($likedPosts) ? $likedPosts->count() : 0 }} recettes aimées)</span>
                        </div>
                    </div>
                    <div class="liked-posts-grid max-h-96 overflow-y-auto">
                        @if(isset($likedPosts) && $likedPosts->count() > 0)
                            @foreach($likedPosts as $like)
                            <div class="foudre-card foudre-card-liked m-3 cursor-pointer" onclick="openPostModal(@json($like->post))">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($like->post && $like->post->image)
                                        <img src="{{ asset($like->post->image) }}" class="w-16 h-16 object-cover rounded-lg" alt="Miniature">
                                        @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <polyline points="21 15 16 10 5 21"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs text-orange-500 font-medium">{{ $like->created_at->diffForHumans() }}</span>
                                            <div class="flex items-center gap-1 text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                                </svg>
                                                <span class="text-xs">{{ $like->post->likes_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($like->post->content ?? '', 80) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Par {{ $like->post->user->name ?? 'Utilisateur' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <p class="text-gray-400">Aucune recette aimée pour le moment</p>
                                <p class="text-xs text-gray-400 mt-1">Explorez la communauté et likez des recettes !</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recettes coup de cœur (recettes likées) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                {{-- Dernière recette likée --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 12h6M12 3v18"/></svg>
                            <h3 class="font-semibold text-gray-800">Dernière recette aimée</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        @if($lastLikedRecette)
                        <a href="/UserHome" class="block">
                            @if($lastLikedRecette->image_path)
                            <div class="h-40 -mx-4 -mt-4 mb-3 overflow-hidden">
                                <img src="{{ asset('storage/' . $lastLikedRecette->image_path) }}" alt="{{ $lastLikedRecette->titre }}" class="w-full h-full object-cover">
                            </div>
                            @endif
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-orange-50 text-orange-600">Recette</span>
                                <div class="flex items-center gap-1 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    <span class="text-xs font-medium">{{ $lastLikedRecette->likes_count ?? 0 }}</span>
                                </div>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-1">{{ $lastLikedRecette->titre }}</h4>
                            <p class="text-xs text-gray-500">par <span class="font-medium text-orange-600">{{ $lastLikedRecette->user->name ?? 'Utilisateur' }}</span></p>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($lastLikedRecette->description, 90) }}</p>
                        </a>
                        @else
                        <div class="text-center py-8">
                            <p class="text-gray-400">Aucune recette aimée pour le moment</p>
                            <p class="text-xs text-gray-400 mt-1">Likez des recettes pour les retrouver ici !</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Liste des recettes likées --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <h3 class="font-semibold text-gray-800">Mes recettes aimées</h3>
                            <span class="text-xs text-gray-400">({{ $likedRecettes->count() }})</span>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto p-3 space-y-3">
                        @forelse($likedRecettes as $like)
                        <a href="/UserHome" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-orange-200 hover:bg-orange-50/40 transition-all">
                            <div class="w-12 h-12 bg-orange-50 rounded-lg overflow-hidden flex items-center justify-center shrink-0">
                                @if($like->recette->image_path)
                                    <img src="{{ asset('storage/' . $like->recette->image_path) }}" alt="{{ $like->recette->titre }}" class="w-full h-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 12h6M12 3v18"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $like->recette->titre }}</p>
                                <p class="text-xs text-gray-400">par {{ $like->recette->user->name ?? 'Utilisateur' }}</p>
                            </div>
                            <div class="flex items-center gap-1 text-red-400 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                <span class="text-xs">{{ $like->recette->likes_count ?? 0 }}</span>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-8">
                            <p class="text-gray-400">Aucune recette aimée pour le moment</p>
                            <p class="text-xs text-gray-400 mt-1">Explorez les recettes et likez vos préférées !</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="border-b border-gray-100 px-4 overflow-x-auto">
                <div class="flex gap-1 min-w-max">
                    <button @click="activeTab = 'infos'" :class="activeTab === 'infos' ? 'tab-btn active' : 'tab-btn'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mes informations
                    </button>
                </div>
            </div>

            {{-- Onglet Informations --}}
            <div x-show="activeTab === 'infos'" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Nom complet
                        </div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            Téléphone
                        </div>
                        <div class="info-value">{{ $user->phone ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            Email
                        </div>
                        <div class="info-value">{{ $user->email ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Ville
                        </div>
                        <div class="info-value">{{ $user->city ?? 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            Date de naissance
                        </div>
                        <div class="info-value">{{ $user->birth_date ? date('d/m/Y', strtotime($user->birth_date)) : 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 12V8H6V4H4v16h8m8-8v8m0-8h-8m8 0v-4h-4"/>
                            </svg>
                            Spécialité
                        </div>
                        <div class="info-value"><span class="specialty-badge">{{ $user->specialty ?? 'Non renseignée' }}</span></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                            Bio
                        </div>
                        <div class="info-value">{{ $user->bio ?? 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Membre depuis
                        </div>
                        <div class="info-value">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

                {{-- Actions dans l'onglet informations --}}
                <div class="info-actions">
                    <button onclick="openEditModal()" class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3l4 4-7 7H10v-4l7-7z"/>
                            <path d="M4 20h16"/>
                        </svg>
                        Modifier le profil
                    </button>
                    <button onclick="openPasswordModal()" class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Changer le mot de passe
                    </button>
                </div>
            </div>

            {{-- Onglet Mes publications --}}
            <div x-show="activeTab === 'posts'" class="p-6">
                @if(isset($recentPosts) && $recentPosts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentPosts as $post)
                        <div class="post-card cursor-pointer" onclick="openPostModal(@json($post))">
                            @if($post->image)
                            <img src="{{ asset($post->image) }}" class="post-image" alt="Publication">
                            @endif
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium
                                            {{ $post->type == 'question' ? 'bg-blue-50 text-blue-700' : '' }}
                                            {{ $post->type == 'realisation' ? 'bg-green-50 text-green-700' : '' }}
                                            {{ $post->type == 'defi' ? 'bg-purple-50 text-purple-700' : '' }}">
                                            @if($post->type == 'question') 
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                                </svg>
                                                Question
                                            @elseif($post->type == 'defi') 
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2z"/>
                                                </svg>
                                                Défi
                                            @else 
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                                    <circle cx="12" cy="13" r="4"/>
                                                </svg>
                                                Réalisation
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ Str::limit($post->content, 100) }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                            </svg> {{ $post->likes_count ?? 0 }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                            </svg> {{ $post->comments_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if(($totalPosts ?? 0) > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('community.user') }}" class="text-orange-500 hover:text-orange-600 text-sm">Voir toutes mes publications →</a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <p class="text-gray-400">Vous n'avez pas encore de publication</p>
                        <a href="{{ route('community.user') }}" class="inline-block mt-3 text-orange-500 hover:text-orange-600 font-medium">Publier maintenant →</a>
                    </div>
                @endif
            </div>

            {{-- Onglet Activités --}}
            <div x-show="activeTab === 'activities'" class="p-6">
                @if(isset($recentComments) && $recentComments->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentComments as $comment)
                        <div class="activity-item">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-700">Vous avez commenté : <span class="font-medium">"{{ Str::limit($comment->content, 80) }}"</span></p>
                                    @if($comment->post)
                                    <p class="text-xs text-gray-400 mt-1">sur : "{{ Str::limit($comment->post->content, 50) }}"</p>
                                    @endif
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                        </svg>
                        <p class="text-gray-400">Aucune activité récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL AVATAR --}}
    <div class="modal-avatar-overlay" :class="showAvatarModal ? 'active' : ''" @click.away="closeAvatarModal()">
        <div class="modal-avatar-content">
            <div class="modal-header">
                <div class="modal-header-title">
                    <div class="brand">
                        <span class="oura">OURA</span><span class="table">TABLE</span>
                    </div>
                    <div class="subtitle">PHOTO DE PROFIL</div>
                </div>
                <button class="modal-close" @click="closeAvatarModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-scroll-area">
                <div style="width:100%;display:flex;flex-direction:column;align-items:center;">
                    <img :src="hasAvatar ? avatarUrl : 'https://ui-avatars.com/api/?background=f97316&color=fff&bold=true&size=400&name={{ urlencode($user->name) }}'" 
                         class="avatar-modal-image" 
                         alt="Avatar">
                    
                    <div class="avatar-modal-actions">
                        <button class="btn-danger" @click="deleteAvatar()" :disabled="avatarDeleting">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Supprimer
                        </button>
                        <button class="btn-secondary" @click="closeAvatarModal()">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DÉTAIL PUBLICATION --}}
    <div id="postModal" class="modal-overlay" onclick="if(event.target === this) closePostModal()">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-title">
                    <div class="brand">
                        <span class="oura">OURA</span><span class="table">TABLE</span>
                    </div>
                    <div class="subtitle">DÉTAIL DE LA PUBLICATION</div>
                </div>
                <button class="modal-close" onclick="closePostModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-scroll-area">
                <div id="postContent">
                    <div class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ÉDITION PROFIL --}}
    <div id="editModal" class="modal-overlay" onclick="if(event.target === this) closeEditModal()">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-title">
                    <div class="brand">
                        <span class="oura">OURA</span><span class="table">TABLE</span>
                    </div>
                    <div class="subtitle">MODIFIER LE PROFIL</div>
                </div>
                <button class="modal-close" onclick="closeEditModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-scroll-area">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <input type="text" x-model="formData.name" @input="formatName()" class="edit-field w-full" :class="errors.name ? 'error' : ''">
                        <div class="error-message" x-text="errors.name"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <div class="flex gap-2">
                            <select x-model="formData.phone_country_code" class="country-select w-28">
                                @foreach($countries as $code => $name)
                                <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                            <input type="tel" x-model="formData.phone" @input="validatePhone()" class="edit-field flex-1" :class="errors.phone ? 'error' : ''" placeholder="Numéro">
                        </div>
                        <div class="error-message" x-text="errors.phone"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" x-model="formData.email" class="edit-field w-full">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <input type="text" x-model="formData.city" class="edit-field w-full" placeholder="Votre ville">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                            <input type="date" x-model="formData.birth_date" class="edit-field w-full">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité culinaire</label>
                        <select x-model="formData.specialty" class="edit-field w-full">
                            <option value="">Sélectionnez une spécialité</option>
                            <option value="Pâtisserie">Pâtisserie</option>
                            <option value="Boulangerie">Boulangerie</option>
                            <option value="Cuisine du Monde">Cuisine du Monde</option>
                            <option value="Cuisine Italienne">Cuisine Italienne</option>
                            <option value="Cuisine Asiatique">Cuisine Asiatique</option>
                            <option value="Cuisine Africaine">Cuisine Africaine</option>
                            <option value="Healthy">Healthy</option>
                            <option value="Barbecue">Barbecue</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea x-model="formData.bio" rows="3" class="edit-field w-full" placeholder="Parlez-nous de vous..."></textarea>
                    </div>
                    
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                            Annuler
                        </button>
                        <button type="button" @click="saveProfile()" class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600 transition">
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CHANGEMENT DE MOT DE PASSE --}}
    <div id="passwordModal" class="modal-overlay" onclick="if(event.target === this) closePasswordModal()">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-title">
                    <div class="brand">
                        <span class="oura">OURA</span><span class="table">TABLE</span>
                    </div>
                    <div class="subtitle">CHANGER LE MOT DE PASSE</div>
                </div>
                <button class="modal-close" onclick="closePasswordModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-scroll-area">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                        <input type="password" x-model="passwordForm.current_password" class="edit-field w-full" placeholder="Entrez votre mot de passe actuel">
                        <div class="error-message" x-text="passwordErrors.current_password"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                        <input type="password" x-model="passwordForm.new_password" class="edit-field w-full" placeholder="Minimum 6 caractères">
                        <div class="error-message" x-text="passwordErrors.new_password"></div>
                        <p class="text-xs text-gray-400 mt-1">Minimum 6 caractères</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input type="password" x-model="passwordForm.new_password_confirmation" class="edit-field w-full" placeholder="Confirmez votre nouveau mot de passe">
                        <div class="error-message" x-text="passwordErrors.new_password_confirmation"></div>
                    </div>
                    
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePasswordModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                            Annuler
                        </button>
                        <button type="button" @click="changePassword()" class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600 transition">
                            Changer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div x-show="showToast" x-cloak class="toast-message" :class="toastType === 'error' ? 'toast-error' : ''">
        <svg x-show="toastType === 'success'" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <svg x-show="toastType === 'error'" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
        <span x-text="toastMessage"></span>
    </div>
</div>

<script>
// Fonctions pour les modales
function openEditModal() {
    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openPasswordModal() {
    document.getElementById('passwordModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openPostModal(postData) {
    const modal = document.getElementById('postModal');
    const content = document.getElementById('postContent');
    
    content.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
        </div>
    `;
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    if (postData && postData.id) {
        displayPostContent(postData);
    } else {
        const id = typeof postData === 'number' ? postData : (postData?.id || 0);
        fetch('/post/' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayPostContent(data.post);
                } else {
                    content.innerHTML = `
                        <div class="text-center py-12 text-gray-500">
                            <p>Erreur lors du chargement de la publication</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                content.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <p>Erreur lors du chargement de la publication</p>
                    </div>
                `;
            });
    }
}

function displayPostContent(post) {
    const content = document.getElementById('postContent');
    
    let imageHtml = '';
    if (post.image) {
        imageHtml = `
            <div style="width:100%;border-radius:1.5rem;overflow:hidden;margin-bottom:1rem;">
                <img src="{{ asset('') }}${post.image}" alt="Publication" style="width:100%;height:auto;max-height:400px;object-fit:contain;display:block;border-radius:1.5rem;">
            </div>
        `;
    }
    
    let typeBadge = '';
    let typeLabel = '';
    if (post.type === 'question') {
        typeBadge = 'background:#eff6ff;color:#1d4ed8;';
        typeLabel = 'Question';
    } else if (post.type === 'defi') {
        typeBadge = 'background:#f3e8ff;color:#6b21a8;';
        typeLabel = 'Défi';
    } else {
        typeBadge = 'background:#f0fdf4;color:#15803d;';
        typeLabel = 'Réalisation';
    }
    
    const userName = post.user?.name || 'Utilisateur';
    const likesCount = post.likes_count || 0;
    const commentsCount = post.comments_count || 0;
    const dateStr = new Date(post.created_at).toLocaleDateString('fr-FR');
    
    content.innerHTML = `
        ${imageHtml}
        
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
            <span style="padding:0.25rem 0.75rem;border-radius:0.75rem;font-size:0.75rem;font-weight:500;${typeBadge}">
                ${typeLabel}
            </span>
            <span style="font-size:0.75rem;color:#9ca3af;">${dateStr}</span>
        </div>
        
        <p style="color:#1f2937;margin-bottom:1rem;line-height:1.6;">${post.content}</p>
        
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;padding-top:0.75rem;border-top:1px solid #f3f4f6;">
            <div style="display:flex;align-items:center;gap:0.25rem;color:#ef4444;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span style="font-size:0.875rem;font-weight:500;">${likesCount}</span>
            </div>
            <div style="display:flex;align-items:center;gap:0.25rem;color:#6b7280;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                <span style="font-size:0.875rem;">${commentsCount}</span>
            </div>
        </div>
        
        <div style="background:#f9fafb;border-radius:0.75rem;padding:0.75rem;">
            <p style="font-size:0.75rem;color:#6b7280;">
                Publié par <span style="font-weight:500;color:#f97316;">${userName}</span>
            </p>
        </div>
    `;
}

function closePostModal() {
    document.getElementById('postModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Fonctions pour l'avatar
function uploadAvatarFile(file) {
    if (!file) return;
    
    const app = document.querySelector('[x-data]');
    if (app && app.__x) {
        app.__x.$data.uploadAvatar(file);
    } else {
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("profile.upload-avatar") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profileAvatar').src = data.avatar + '?t=' + Date.now();
                const app2 = document.querySelector('[x-data]');
                if (app2 && app2.__x) {
                    app2.__x.$data.hasAvatar = true;
                    app2.__x.$data.avatarUrl = data.avatar + '?t=' + Date.now();
                }
                showToastMessage(data.message, 'success');
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
}

function showToastMessage(message, type) {
    const app = document.querySelector('[x-data]');
    if (app && app.__x) {
        app.__x.$data.showMessage(message, type);
    }
}
</script>

@endsection