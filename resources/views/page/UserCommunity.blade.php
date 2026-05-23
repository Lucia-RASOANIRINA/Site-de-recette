@extends('layouts.UserHeader')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes likeBounce {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }

    @keyframes floatIn {
        0% { opacity: 0; transform: translateY(30px); filter: blur(5px); }
        100% { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.9) rotate(-2deg); }
        100% { opacity: 1; transform: scale(1) rotate(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-float-in { animation: floatIn 0.6s ease forwards; }
    .animate-modal-pop { animation: modalPop 0.3s ease; }
    .like-animation { animation: likeBounce 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }

    .loader-orange {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #f97316;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-right: 8px;
    }

    /* Layout principal */
    .main-container {
        max-width: 1400px;
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
        cursor: pointer;
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

    /* Layout deux colonnes */
    .two-col-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 900px) {
        .two-col-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    /* IA Challenge card */
    .ia-challenge-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        transition: all 0.3s ease;
        padding: 24px;
        height: 100%;
    }
    .ia-challenge-card:hover {
        border-color: #f97316;
        box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Formulaire publication */
    .post-form-card {
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #e5e7eb;
    }
    .post-form-header {
        background: #fef3c7;
        padding: 16px 20px;
        border-bottom: 1px solid #fde68a;
    }

    /* Image container */
    .image-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 1.5rem;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
    }

    .image-frame {
        position: absolute;
        inset: -2px;
        border: 3px solid white;
        border-radius: 1.6rem;
        z-index: 20;
        pointer-events: none;
    }

    .image-backdrop {
        position: absolute;
        inset: 0;
        background: #f97316;
        border-radius: 1.5rem;
        transform: rotate(2deg) scale(1.03);
        opacity: 0.2;
        transition: all 0.6s ease;
        filter: blur(4px);
    }

    .image-main {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 1.3rem;
        transition: all 0.6s ease;
        z-index: 10;
    }

    .image-container:hover .image-main { transform: scale(1.05); }
    .image-container:hover .image-backdrop { transform: rotate(3deg) scale(1.08); opacity: 0.3; }

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
        padding: 6px 14px;
        border-radius: 2rem;
        font-size: 13px;
        cursor: pointer;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .interaction-btn:hover { 
        background: #fef3c7; 
        border-color: #f97316; 
        transform: scale(1.08);
    }

    .like-btn-active {
        background: #fee2e2;
        border-color: #ef4444;
        color: #ef4444;
    }
    .like-btn-active i {
        color: #ef4444;
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
        padding: 8px 22px;
        border-radius: 2rem;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .filter-btn.active { 
        background: #f97316; 
        color: white; 
        border-color: #f97316; 
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
    }
    .filter-btn:hover:not(.active) { border-color: #f97316; color: #f97316; transform: translateY(-2px); }

    /* Type selector */
    .type-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }
    .type-option {
        flex: 1;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        background: white;
    }
    .type-option.selected {
        border-color: #f97316;
        background: #fef3c7;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .type-option i {
        font-size: 22px;
        margin-bottom: 6px;
        display: block;
    }
    .type-option span {
        font-size: 12px;
        font-weight: 600;
    }

    /* Image upload */
    .post-image-upload {
        border: 2px dashed #e5e7eb;
        border-radius: 1rem;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 16px;
        background: #fafafa;
    }
    .post-image-upload:hover {
        border-color: #f97316;
        background: #fef3c7;
    }
    .post-image-preview {
        position: relative;
        display: inline-block;
        margin-top: 12px;
    }
    .post-image-preview img {
        max-height: 150px;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .post-remove-image {
        position: absolute;
        top: -12px;
        right: -12px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    .post-remove-image:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* Challenge image upload */
    .challenge-image-upload {
        border: 2px dashed #e5e7eb;
        border-radius: 1rem;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 16px;
        background: #fafafa;
    }
    .challenge-image-upload:hover {
        border-color: #f97316;
        background: #fef3c7;
    }
    .challenge-image-preview {
        position: relative;
        display: inline-block;
        margin-top: 12px;
    }
    .challenge-image-preview img {
        max-height: 150px;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .challenge-remove-image {
        position: absolute;
        top: -12px;
        right: -12px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    .challenge-remove-image:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* Buttons */
    .btn-orange { 
        background: #f97316;
        color: white; 
        padding: 10px 24px; 
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
    .btn-orange:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    /* Modals */
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

    /* Custom dialog */
    .custom-dialog {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-dialog-content {
        background: white;
        border-radius: 1.5rem;
        max-width: 380px;
        width: 90%;
        padding: 28px;
        text-align: center;
        animation: modalPop 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .custom-dialog-content h3 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 12px;
        color: #1f2937;
    }
    .custom-dialog-content p {
        color: #6b7280;
        margin-bottom: 24px;
    }
    .custom-dialog-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    .custom-dialog-buttons button {
        padding: 10px 24px;
        border-radius: 2rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .dialog-confirm {
        background: #f97316;
        color: white;
        border: none;
    }
    .dialog-confirm:hover { background: #ea580c; transform: scale(1.02); }
    .dialog-cancel {
        background: white;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }
    .dialog-cancel:hover { border-color: #f97316; color: #f97316; }

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

    /* Three dots menu */
    .three-dots-menu {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .three-dots-menu:hover {
        background: #fef3c7;
    }

    /* Group menu */
    .group-menu-btn { position: relative; }
    .group-menu {
        position: absolute;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        z-index: 100;
        min-width: 160px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    .group-menu-bottom {
        top: 100%;
        right: 0;
        margin-top: 8px;
    }
    .group-menu-top {
        bottom: 100%;
        right: 0;
        margin-bottom: 8px;
    }
    .group-menu-item {
        padding: 12px 16px;
        transition: all 0.2s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        width: 100%;
        background: white;
        border: none;
        text-align: left;
        font-weight: 500;
    }
    .group-menu-item:hover { background: #fef3c7; }
    .group-menu-item-danger { color: #dc2626; }
    .group-menu-item-danger:hover { background: #fee2e2; }
    .group-menu-item-success { color: #f97316; }
    .group-menu-item-success:hover { background: #fef3c7; }

    /* Member item */
    .member-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }
    .member-item:hover { background: #fefce8; }
    .member-avatar {
        width: 34px;
        height: 34px;
        background: #f97316;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Messenger styles */
    .messenger-toggle {
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
        z-index: 1000;
    }
    .messenger-toggle:hover { transform: scale(1.08); background: #ea580c; }
    .messenger-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        border: 2px solid white;
    }
    .messenger-window {
        position: fixed;
        bottom: 90px;
        right: 24px;
        width: 380px;
        height: 520px;
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 1000;
        animation: slideUp 0.3s ease;
        border: 1px solid #f97316;
    }
    @media (max-width: 480px) {
        .messenger-window {
            width: calc(100% - 32px);
            right: 16px;
            bottom: 80px;
            height: 480px;
        }
    }
    .messenger-header {
        background: #f97316;
        color: white;
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .messenger-tabs {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        background: white;
    }
    .messenger-tab {
        flex: 1;
        text-align: center;
        padding: 12px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .messenger-tab.active { color: #f97316; border-bottom: 2px solid #f97316; }
    .messenger-list { flex: 1; overflow-y: auto; padding: 8px; }
    .conversation-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: background 0.2s;
        position: relative;
    }
    .conversation-item:hover { background: #fef3c7; }
    .conversation-avatar {
        width: 48px;
        height: 48px;
        background: #f97316;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .conversation-info { flex: 1; min-width: 0; }
    .conversation-name { font-weight: 600; font-size: 14px; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .conversation-last-message { font-size: 11px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .unread-badge { background: #f97316; color: white; border-radius: 50%; min-width: 20px; height: 20px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    .chat-area { display: flex; flex-direction: column; height: 100%; }
    .chat-header { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 12px; }
    .back-btn { cursor: pointer; padding: 8px; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
    .back-btn:hover { background: #f3f4f6; }
    .messages-container { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; }
    .message-bubble { max-width: 80%; padding: 8px 14px; border-radius: 1.2rem; word-wrap: break-word; font-size: 13px; line-height: 1.4; }
    .message-own { background: #f97316; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
    .message-other { background: #f3f4f6; color: #1f2937; align-self: flex-start; border-bottom-left-radius: 4px; }
    .message-time { font-size: 9px; margin-top: 4px; opacity: 0.7; }
    .message-input-area { padding: 12px 16px; border-top: 1px solid #e5e7eb; display: flex; gap: 10px; background: white; }
    .message-input { flex: 1; padding: 10px 16px; border: 1px solid #e5e7eb; border-radius: 2rem; outline: none; font-size: 13px; transition: all 0.2s; }
    .message-input:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2); }
    .send-btn { padding: 8px 20px; background: #f97316; color: white; border: none; border-radius: 2rem; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
    .send-btn:hover { transform: scale(1.03); background: #ea580c; }
    .empty-messages { text-align: center; color: #9ca3af; padding: 40px; font-size: 13px; }
    
    .messenger-footer { padding: 10px 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-start; background: white; }
    .new-conv-footer-btn {
        background: #f97316;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .new-conv-footer-btn:hover { background: #ea580c; transform: scale(1.05); }

    .group-detail-avatar {
        width: 64px;
        height: 64px;
        background: #f97316;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* User select item */
    .user-select-item {
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 1rem;
        border: 1px solid #f0f0f0;
    }
    .user-select-item:hover { background: #fef3c7; transform: translateX(5px); border-color: #f97316; }

    /* Edit modal */
    .edit-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1100;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .edit-modal-content {
        background: white;
        border-radius: 1.5rem;
        max-width: 500px;
        width: 90%;
        padding: 24px;
        border: 1px solid #e5e7eb;
    }

    /* Comment edit inline */
    .comment-edit-form {
        display: none;
        margin-top: 8px;
    }
    .comment-edit-form.active {
        display: flex;
        gap: 8px;
    }
    .comment-edit-input {
        flex: 1;
        border: 1px solid #f97316;
        border-radius: 1rem;
        padding: 6px 12px;
        font-size: 13px;
        outline: none;
    }
    .comment-edit-input:focus {
        ring: 2px solid #f97316;
    }
    .comment-text.hidden {
        display: none;
    }
</style>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="app()" x-init="init()" class="min-h-screen bg-gray-50 pb-24">
    
    <div class="main-container">
        
        {{-- HERO SECTION --}}
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
                    <div class="hero-stat" onclick="openStatsModal('posts', {{ $totalPosts }})">
                        <div class="hero-stat-number">{{ $totalPosts }}</div>
                        <div class="hero-stat-label">Publications</div>
                    </div>
                    <div class="hero-stat" onclick="openStatsModal('members', {{ $totalMembres }})">
                        <div class="hero-stat-number">{{ $totalMembres }}</div>
                        <div class="hero-stat-label">Membres</div>
                    </div>
                    <div class="hero-stat" onclick="openStatsModal('comments', {{ $totalComments }})">
                        <div class="hero-stat-number">{{ $totalComments }}</div>
                        <div class="hero-stat-label">Commentaires</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRILLE 2 COLONNES : DÉFI + PUBLICATION --}}
        <div class="two-col-grid">
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
                
                <button onclick="openChallengePostModal({{ json_encode($aiChallenge->ingredients) }})" class="w-full btn-orange flex items-center justify-center gap-2">
                    <i data-lucide="award" class="w-4 h-4"></i> Participer au défi
                </button>
            </div>
            @endif

            {{-- FORMULAIRE DE PUBLICATION --}}
            <div class="post-form-card">
                <div class="post-form-header">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-md">
                            <i data-lucide="pen-tool" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Partagez votre passion</h3>
                            <p class="text-xs text-gray-500">Une réalisation, une question...</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-5 flex-1">
                    <form id="mainPostForm" onsubmit="event.preventDefault(); submitPost()">
                        @csrf
                        <div class="type-selector">
                            <div id="typeRealisation" class="type-option" onclick="selectPostType('realisation')">
                                <i data-lucide="cooking-pot"></i>
                                <span>Réalisation</span>
                            </div>
                            <div id="typeQuestion" class="type-option" onclick="selectPostType('question')">
                                <i data-lucide="help-circle"></i>
                                <span>Question</span>
                            </div>
                        </div>
                        <input type="hidden" name="type" id="selectedPostType" value="realisation">
                        
                        <textarea name="content" id="postContent" rows="3" class="w-full border-2 border-gray-100 rounded-xl p-3 mb-4 focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition text-sm" placeholder="Qu'avez-vous cuisiné aujourd'hui ? Une question technique ? ..." required></textarea>
                        
                        <div id="postImageUploadArea" class="post-image-upload" onclick="document.getElementById('postImageInput').click()">
                            <i data-lucide="image" class="w-5 h-5 text-gray-400 mb-1"></i>
                            <p class="text-xs text-gray-500">Cliquez pour ajouter une photo</p>
                            <input type="file" id="postImageInput" class="hidden" accept="image/*" onchange="previewPostImage(this)">
                        </div>
                        <div id="postImagePreviewContainer" class="post-image-preview" style="display: none;">
                            <img id="postImagePreviewImg" src="" alt="Aperçu">
                            <div class="post-remove-image" onclick="removePostImage()">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                        <input type="file" name="image" id="postImageFile" class="hidden">
                        
                        <button type="submit" id="submitPostBtn" class="w-full btn-orange flex items-center justify-center gap-2 mt-3">
                            <i data-lucide="send" class="w-4 h-4"></i> Publier
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- FILTRES --}}
        <div class="flex flex-wrap gap-2 mb-6 justify-center">
            <button @click="setFilter('all')" :class="activeFilter === 'all' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Tous
            </button>
            <button @click="setFilter('realisation')" :class="activeFilter === 'realisation' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="cooking-pot" class="w-3.5 h-3.5"></i> Réalisations
            </button>
            <button @click="setFilter('question')" :class="activeFilter === 'question' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="help-circle" class="w-3.5 h-3.5"></i> Questions
            </button>
            <button @click="setFilter('defi')" :class="activeFilter === 'defi' ? 'active' : ''" class="filter-btn transition-all">
                <i data-lucide="trophy" class="w-3.5 h-3.5"></i> Défis
            </button>
        </div>

        {{-- LISTE DES POSTS --}}
        <div class="posts-container">
            @forelse($posts as $post)
            @php
                $lastComment = $post->comments->last();
                $postCommentsCount = $post->comments_count ?? $post->comments->count();
                $isOwner = (Auth::id() == $post->user_id);
                $userLiked = $post->likes->contains('user_id', Auth::id());
            @endphp
            <div x-data="{ 
                    likesCount: {{ $post->likes_count ?? $post->likes->count() }},
                    userLiked: {{ $userLiked ? 'true' : 'false' }},
                    showComments: false,
                    showMenu: false
                 }"
                 x-show="activeFilter === 'all' || activeFilter === '{{$post->type}}'"
                 x-transition:enter="transition-all duration-400 ease-out"
                 x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="post-card">
                
                <div class="p-5">
                    {{-- EN-TETE --}}
                    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md text-sm">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-gray-900 text-sm">{{ $post->user->name }}</span>
                                    <i data-lucide="badge-check" class="w-3.5 h-3.5 text-orange-500"></i>
                                    @if(!$isOwner)
                                    <button onclick="startConversationWith({{ $post->user->id }}, '{{ addslashes($post->user->name) }}')" class="text-orange-400 hover:text-orange-600 transition-colors" title="Envoyer un message">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                    </button>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-xl text-[11px] font-bold uppercase tracking-wider flex items-center gap-1
                                {{ $post->type == 'question' ? 'bg-blue-50 text-blue-600' : '' }}
                                {{ $post->type == 'realisation' ? 'bg-orange-50 text-orange-600' : '' }}
                                {{ $post->type == 'defi' ? 'bg-purple-50 text-purple-600' : '' }}">
                                @if($post->type == 'question') 
                                    <i data-lucide="help-circle" class="w-3 h-3"></i> Question
                                @elseif($post->type == 'defi')
                                    <i data-lucide="trophy" class="w-3 h-3"></i> Défi
                                @else 
                                    <i data-lucide="cooking-pot" class="w-3 h-3"></i> Réalisation
                                @endif
                            </span>
                            @if($isOwner)
                            <div class="relative">
                                <button @click="showMenu = !showMenu" class="three-dots-menu">
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <div x-show="showMenu" @click.away="showMenu = false" class="absolute right-0 top-full mt-1 bg-white rounded-xl shadow-lg border z-50 min-w-[140px] overflow-hidden">
                                    <button onclick="editPostPrompt({{ $post->id }}, '{{ addslashes($post->content) }}', '{{ $post->image_path ? asset('storage/'.$post->image_path) : '' }}', '{{ $post->type }}')" class="w-full px-3 py-2.5 text-left text-sm hover:bg-orange-50 flex items-center gap-2 transition">
                                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Modifier
                                    </button>
                                    <button onclick="confirmDeletePost({{ $post->id }})" class="w-full px-3 py-2.5 text-left text-sm hover:bg-red-50 text-red-600 flex items-center gap-2 transition">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- CONTENU --}}
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">{{ $post->content }}</p>

                    {{-- IMAGE --}}
                    @if($post->image_path)
                    <div class="mb-4">
                        <div class="image-container">
                            <div class="image-backdrop"></div>
                            <div class="image-frame"></div>
                            <img src="{{ asset('storage/'.$post->image_path) }}" class="image-main" alt="Post image">
                        </div>
                    </div>
                    @endif

                    {{-- INTERACTIONS --}}
                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100 flex-wrap">
                        <button onclick="likePost({{ $post->id }}, this)" 
                                :class="userLiked ? 'like-btn-active' : ''"
                                class="interaction-btn like-btn">
                            <i :data-lucide="userLiked ? 'heart' : 'heart'" class="w-3.5 h-3.5" :style="userLiked ? 'color: #ef4444;' : ''"></i>
                            <span id="likes-{{ $post->id }}" x-text="likesCount">{{ $post->likes_count ?? $post->likes->count() }}</span>
                        </button>

                        <button @click="showComments = !showComments" class="interaction-btn">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            <span id="comments-count-{{ $post->id }}">{{ $postCommentsCount }}</span>
                        </button>

                        <button onclick="sharePost({{ $post->id }})" class="interaction-btn ml-auto">
                            <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                            Partager
                        </button>
                    </div>

                    {{-- COMMENTAIRES --}}
                    <div x-show="showComments" x-collapse class="mt-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div id="comments-list-{{ $post->id }}" class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                @forelse($post->comments as $comment)
                                <div class="bg-white rounded-lg p-2.5 shadow-sm" id="comment-{{ $comment->id }}">
                                    <div class="flex items-start gap-2">
                                        <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 font-bold text-xs flex-shrink-0">{{ substr($comment->user->name, 0, 1) }}</div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                                <span class="font-semibold text-gray-800 text-xs">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="comment-text text-gray-600 text-sm" id="comment-text-{{ $comment->id }}">{{ $comment->content }}</p>
                                            <div class="comment-edit-form" id="comment-edit-{{ $comment->id }}">
                                                <input type="text" class="comment-edit-input" id="comment-edit-input-{{ $comment->id }}" value="{{ $comment->content }}">
                                                <button onclick="saveCommentEdit({{ $comment->id }}, {{ $post->id }})" class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs">Sauvegarder</button>
                                                <button onclick="cancelCommentEdit({{ $comment->id }})" class="bg-gray-300 text-gray-700 px-3 py-1 rounded-full text-xs">Annuler</button>
                                            </div>
                                        </div>
                                        @if(Auth::id() == $comment->user_id)
                                        <div class="flex items-center gap-1">
                                            <button onclick="editCommentInline({{ $comment->id }})" class="text-gray-400 hover:text-orange-500 transition" title="Modifier">
                                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                                            </button>
                                            <button onclick="deleteComment({{ $comment->id }}, {{ $post->id }})" class="text-gray-400 hover:text-red-500 transition" title="Supprimer">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-400 text-center text-sm py-3">Aucun commentaire</p>
                                @endforelse
                            </div>
                            <form onsubmit="event.preventDefault(); submitComment(this, {{ $post->id }})" class="mt-3">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="flex gap-2">
                                    <input type="text" name="content" placeholder="Écrire un commentaire..." class="flex-1 border border-gray-200 rounded-full px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500" required>
                                    <button type="submit" class="bg-orange-500 text-white px-4 rounded-full text-sm hover:bg-orange-600 transition flex items-center gap-1">
                                        <i data-lucide="send" class="w-3.5 h-3.5"></i> Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- DERNIER COMMENTAIRE --}}
                    @if($lastComment)
                    <div class="mt-3 last-comment-card p-3">
                        <div class="flex items-start gap-2">
                            <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 text-xs flex-shrink-0">{{ substr($lastComment->user->name, 0, 1) }}</div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $lastComment->content }}</p>
                                <div class="text-xs text-gray-400 mt-0.5">— {{ $lastComment->user->name }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border p-12 text-center">
                <i data-lucide="utensils" class="w-14 h-14 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-400">Aucune publication pour le moment</p>
                <p class="text-xs text-gray-300 mt-2">Soyez le premier à partager !</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL STATISTIQUES --}}
    <div x-show="showStatsModal" x-cloak class="stats-modal-overlay" @click.away="showStatsModal = false">
        <div class="stats-modal-content">
            <div class="stats-modal-header">
                <i :data-lucide="statsIcon"></i>
                <h2 x-text="statsTitle"></h2>
            </div>
            <div class="stats-modal-body">
                <div class="stats-modal-number" x-text="statsValue"></div>
                <p x-text="statsDescription"></p>
                <button class="stats-modal-close" @click="showStatsModal = false">
                    <i data-lucide="check" class="w-4 h-4"></i> Fermer
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL DÉFI --}}
    <div x-show="showPostModal" x-cloak class="modal-overlay" @click.away="showPostModal = false">
        <div class="modal-content p-5">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="trophy" class="w-4 h-4 text-white"></i>
                    </div>
                    <h3 class="font-bold text-lg">Participer au défi</h3>
                </div>
                <button @click="showPostModal = false" class="text-gray-400 text-xl hover:text-gray-600 transition">&times;</button>
            </div>
            <form id="challengeFormData" onsubmit="event.preventDefault(); submitChallengePost()">
                @csrf
                <input type="hidden" name="type" value="defi">
                <textarea id="challengeContent" class="w-full border-2 border-gray-100 rounded-xl p-3 mb-3 focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition" rows="4" placeholder="Décrivez votre création pour ce défi..." required></textarea>
                <p id="ingredientsWarning" class="text-xs text-orange-500 hidden mt-1 mb-2 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3"></i> Pensez à mentionner les ingrédients clés !
                </p>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-2 flex items-center gap-1">
                        <i data-lucide="camera" class="w-3 h-3"></i> Photo de votre réalisation
                    </label>
                    <div id="challengeImageUploadArea" class="challenge-image-upload" onclick="document.getElementById('challengeImageInput').click()">
                        <i data-lucide="image" class="w-5 h-5 text-gray-400 mb-1"></i>
                        <p class="text-xs text-gray-500">Cliquez pour ajouter une photo</p>
                        <input type="file" id="challengeImageInput" class="hidden" accept="image/*" onchange="previewChallengeImage(this)">
                    </div>
                    <div id="challengeImagePreviewContainer" class="challenge-image-preview" style="display: none;">
                        <img id="challengeImagePreviewImg" src="" alt="Aperçu">
                        <div class="challenge-remove-image" onclick="removeChallengeImage()">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                    <input type="file" name="image" id="challengeImageFile" class="hidden">
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showPostModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i> Annuler
                    </button>
                    <button type="submit" id="challengeSubmitBtn" class="flex-1 btn-orange flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Publier ma participation
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DÉTAILS GROUPE --}}
    <div x-show="showGroupDetailModal" x-cloak class="modal-overlay" @click.away="showGroupDetailModal = false">
        <div class="modal-content p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-orange-500"></i>
                    <span x-text="selectedGroup?.name"></span>
                </h3>
                <button @click="showGroupDetailModal = false" class="text-gray-400 text-xl hover:text-gray-600 transition">&times;</button>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <div class="group-detail-avatar w-14 h-14 text-xl" x-text="selectedGroup?.name?.substring(0,2).toUpperCase()"></div>
                <div>
                    <div class="text-sm text-gray-500 flex items-center gap-1">
                        <i data-lucide="users" class="w-3 h-3"></i> <span x-text="selectedGroup?.member_count + ' membres'"></span>
                    </div>
                    <div class="text-xs text-gray-400 flex items-center gap-1 mt-1">
                        <i data-lucide="folder" class="w-3 h-3"></i> Catégorie: <span x-text="selectedGroup?.category"></span>
                    </div>
                </div>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed mb-4" x-text="selectedGroup?.description"></p>
            <div class="mb-4">
                <h4 class="font-semibold text-gray-800 mb-2 text-sm flex items-center gap-1">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i> Membres
                </h4>
                <div class="max-h-48 overflow-y-auto border rounded-xl p-2 custom-scrollbar">
                    <template x-for="member in groupMembers" :key="member.id">
                        <div class="member-item">
                            <div class="member-avatar" x-text="member.initials || member.name?.substring(0,2).toUpperCase()"></div>
                            <div class="flex-1"><span class="text-sm font-medium" x-text="member.name"></span></div>
                        </div>
                    </template>
                    <div x-show="groupMembers.length === 0" class="text-center text-gray-400 text-sm py-6">
                        <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        Aucun membre pour le moment
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t">
                <button id="groupActionBtn" class="w-full btn-orange flex items-center justify-center gap-2"></button>
            </div>
        </div>
    </div>

    {{-- MODAL NOUVELLE CONVERSATION --}}
    <div x-show="showMessageUserModal" x-cloak class="modal-overlay" @click.away="showMessageUserModal = false">
        <div class="modal-content max-w-md p-5">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="message-circle" class="w-4 h-4 text-white"></i>
                    </div>
                    <h3 class="font-bold text-lg">Nouvelle conversation</h3>
                </div>
                <button @click="showMessageUserModal = false" class="text-gray-400 text-xl hover:text-gray-600 transition">&times;</button>
            </div>
            <input type="text" x-model="searchUser" @input="filterUsers" placeholder="Rechercher un membre..." class="w-full border border-gray-200 rounded-xl px-4 py-3 mb-4 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
            <div class="max-h-96 overflow-y-auto space-y-2 custom-scrollbar">
                <template x-for="user in filteredUsers" :key="user.id">
                    <div @click="startConversationWithUser(user.id, user.name)" class="user-select-item">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white font-bold shadow-sm" x-text="user.initials"></div>
                        <div class="flex-1">
                            <div class="font-medium text-sm" x-text="user.name"></div>
                            <div class="text-xs text-gray-400">Membre</div>
                        </div>
                        <i data-lucide="message-circle" class="w-5 h-5 text-orange-400"></i>
                    </div>
                </template>
                <div x-show="filteredUsers.length === 0" class="text-center text-gray-400 py-10">
                    <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">Aucun membre trouvé</p>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ÉDITION --}}
    <div id="editPostModal" class="edit-modal-overlay" style="display: none;">
        <div class="edit-modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <i data-lucide="edit-2" class="w-4 h-4 text-orange-500"></i>
                    Modifier la publication
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 text-xl hover:text-gray-600 transition">&times;</button>
            </div>
            <textarea id="editPostContent" class="w-full border-2 border-gray-100 rounded-xl p-3 mb-3 focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition" rows="4"></textarea>
            <div id="editImageContainer" class="mb-3"></div>
            <div id="editImageUploadArea" class="post-image-upload mb-3" onclick="document.getElementById('editPostImageInput').click()" style="padding: 12px;">
                <i data-lucide="image" class="w-4 h-4 text-gray-400 mb-1"></i>
                <p class="text-xs text-gray-500">Changer la photo</p>
                <input type="file" id="editPostImageInput" class="hidden" accept="image/*" onchange="previewEditImage(this)">
            </div>
            <div id="editRemoveImageBtn" class="hidden mb-4">
                <button type="button" onclick="removeEditImage()" class="w-full bg-red-100 text-red-600 py-2 rounded-xl text-sm font-medium hover:bg-red-200 transition flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer l'image
                </button>
            </div>
            <div class="flex gap-3">
                <button onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    <i data-lucide="x" class="w-4 h-4"></i> Annuler
                </button>
                <button onclick="saveEditPost()" class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600 transition flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- TOAST NOTIFICATION --}}
<div id="toastNotification" class="toast-notification">
    <i id="toastIcon" data-lucide="party-popper" class="w-4 h-4"></i>
    <span id="toastMessage"></span>
</div>

{{-- MESSAGERIE --}}
<div id="messengerApp"></div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
//  VARIABLES GLOBALES
let editingPostId = null;
let editingPostHasImage = false;
let currentPostImageFile = null;
let currentChallengeImageFile = null;
let currentIngredients = [];
let alpineApp = null;
let activeGroupMenu = null;

//  FONCTIONS UTILITAIRES
function showToast(message, isSuccess = true, icon = 'party-popper') {
    const toast = document.getElementById('toastNotification');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    toastIcon.setAttribute('data-lucide', icon);
    
    if (isSuccess) {
        toast.style.background = '#f97316';
    } else {
        toast.style.background = '#ef4444';
    }
    
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3500);
    setTimeout(() => initLucide(), 100);
}

function customConfirm(message, onConfirm) {
    const dialog = document.createElement('div');
    dialog.className = 'custom-dialog';
    dialog.innerHTML = `
        <div class="custom-dialog-content">
            <i data-lucide="alert-triangle" class="w-12 h-12 text-orange-500 mx-auto mb-3"></i>
            <h3>Confirmation</h3>
            <p>${message}</p>
            <div class="custom-dialog-buttons">
                <button class="dialog-cancel flex items-center gap-1"><i data-lucide="x" class="w-3.5 h-3.5"></i> Annuler</button>
                <button class="dialog-confirm flex items-center gap-1"><i data-lucide="check" class="w-3.5 h-3.5"></i> Confirmer</button>
            </div>
        </div>
    `;
    document.body.appendChild(dialog);
    setTimeout(() => initLucide(), 100);
    dialog.querySelector('.dialog-confirm').onclick = () => { dialog.remove(); if(onConfirm) onConfirm(); };
    dialog.querySelector('.dialog-cancel').onclick = () => dialog.remove();
    
    dialog.addEventListener('click', function(e) {
        if (e.target === dialog) {
            dialog.remove();
        }
    });
}

function showLoader(btn, originalText) {
    btn.innerHTML = '<div class="loader-orange"></div> Chargement...';
    btn.disabled = true;
}

function hideLoader(btn, originalText) {
    btn.innerHTML = originalText;
    btn.disabled = false;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function initLucide() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function getAlpineApp() {
    if (!alpineApp) {
        const alpineElement = document.querySelector('[x-data]');
        if (alpineElement && alpineElement.__x) {
            alpineApp = alpineElement.__x.$data;
        }
    }
    return alpineApp;
}

// Fermer les menus au clic ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.group-menu-btn') && !e.target.closest('.group-menu')) {
        if (activeGroupMenu) {
            activeGroupMenu.classList.add('hidden');
            activeGroupMenu = null;
        }
    }
    const messengerWindow = document.querySelector('.messenger-window');
    const messengerToggle = document.querySelector('.messenger-toggle');
    if (messengerWindow && messengerWindow.style.display === 'flex') {
        if (!messengerWindow.contains(e.target) && !messengerToggle.contains(e.target)) {
            if (typeof messengerState !== 'undefined' && messengerState.isOpen) {
                toggleMessenger();
            }
        }
    }
});

// STATS MODALS 
function openStatsModal(type, value) {
    const app = getAlpineApp();
    if (app) {
        if (type === 'posts') {
            app.statsTitle = 'Publications';
            app.statsValue = value;
            app.statsDescription = 'Total des publications partagées par la communauté des Coeurs Gourmands.';
            app.statsIcon = 'file-text';
        } else if (type === 'members') {
            app.statsTitle = 'Membres';
            app.statsValue = value;
            app.statsDescription = 'Nombre de gourmets passionnés qui font vivre cette communauté.';
            app.statsIcon = 'users';
        } else if (type === 'comments') {
            app.statsTitle = 'Commentaires';
            app.statsValue = value;
            app.statsDescription = 'Total des échanges et discussions entre les membres.';
            app.statsIcon = 'message-circle';
        }
        app.showStatsModal = true;
        setTimeout(() => initLucide(), 100);
    }
}

// GESTION DES POSTS =
function selectPostType(type) {
    const typeRealisation = document.getElementById('typeRealisation');
    const typeQuestion = document.getElementById('typeQuestion');
    const imageUploadArea = document.getElementById('postImageUploadArea');
    const postImagePreview = document.getElementById('postImagePreviewContainer');
    
    document.getElementById('selectedPostType').value = type;
    
    if (type === 'realisation') {
        typeRealisation.classList.add('selected');
        typeQuestion.classList.remove('selected');
        imageUploadArea.style.display = 'block';
    } else {
        typeQuestion.classList.add('selected');
        typeRealisation.classList.remove('selected');
        imageUploadArea.style.display = 'none';
        postImagePreview.style.display = 'none';
        document.getElementById('postImageFile').value = '';
        currentPostImageFile = null;
    }
}

function previewPostImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('postImagePreviewImg').src = e.target.result;
            document.getElementById('postImagePreviewContainer').style.display = 'inline-block';
            document.getElementById('postImageUploadArea').style.display = 'none';
            currentPostImageFile = input.files[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(input.files[0]);
            document.getElementById('postImageFile').files = dataTransfer.files;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePostImage() {
    document.getElementById('postImagePreviewContainer').style.display = 'none';
    document.getElementById('postImageUploadArea').style.display = 'block';
    document.getElementById('postImageInput').value = '';
    document.getElementById('postImageFile').value = '';
    currentPostImageFile = null;
}

function submitPost() {
    const content = document.getElementById('postContent').value.trim();
    const postType = document.getElementById('selectedPostType').value;
    
    if (!content) {
        showToast('Veuillez écrire quelque chose', false, 'alert-circle');
        return;
    }
    
    const submitBtn = document.getElementById('submitPostBtn');
    const originalText = submitBtn.innerHTML;
    showLoader(submitBtn, originalText);
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('content', content);
    formData.append('type', postType);
    if (currentPostImageFile) {
        formData.append('image', currentPostImageFile);
    }
    
    fetch('/community/post', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Publication publiée avec succès !', true, 'party-popper');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast('Erreur lors de la publication', false, 'alert-circle');
            hideLoader(submitBtn, originalText);
        }
    })
    .catch(() => {
        showToast('Erreur lors de la publication', false, 'alert-circle');
        hideLoader(submitBtn, originalText);
    });
}

// LIKE DYNAMIQUE RAPIDE 
function likePost(postId, btn) {
    // Récupérer l'état actuel
    const isLiked = btn.classList.contains('like-btn-active');
    const likesSpan = document.getElementById(`likes-${postId}`);
    let currentLikes = parseInt(likesSpan.innerText);
    
    // Mise à jour UI instantanée (optimiste)
    if (isLiked) {
        btn.classList.remove('like-btn-active');
        currentLikes--;
        const heartIcon = btn.querySelector('i');
        if (heartIcon) {
            heartIcon.style.color = '';
        }
        showToast('Vous n\'aimez plus cette publication', false, 'heart-off');
    } else {
        btn.classList.add('like-btn-active');
        currentLikes++;
        const heartIcon = btn.querySelector('i');
        if (heartIcon) {
            heartIcon.style.color = '#ef4444';
            heartIcon.classList.add('like-animation');
            setTimeout(() => heartIcon.classList.remove('like-animation'), 300);
        }
        showToast('Vous avez aimé cette publication !', true, 'heart');
    }
    
    // Mettre à jour l'affichage
    likesSpan.innerText = currentLikes;
    
    // Désactiver temporairement le bouton pour éviter les doubles clics
    btn.disabled = true;
    
    // Envoyer la requête au serveur
    fetch(`/community/like/${postId}`, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Synchroniser avec la valeur réelle du serveur
            likesSpan.innerText = data.likes;
            
            // Mettre à jour le state Alpine
            const postCard = btn.closest('.post-card');
            if (postCard && postCard.__x) {
                postCard.__x.$data.userLiked = data.liked;
                postCard.__x.$data.likesCount = data.likes;
            }
            
            // S'assurer que le style est correct
            if (data.liked) {
                btn.classList.add('like-btn-active');
                const heartIcon = btn.querySelector('i');
                if (heartIcon) heartIcon.style.color = '#ef4444';
            } else {
                btn.classList.remove('like-btn-active');
                const heartIcon = btn.querySelector('i');
                if (heartIcon) heartIcon.style.color = '';
            }
        } else {
            // En cas d'erreur, annuler les changements
            if (isLiked) {
                btn.classList.add('like-btn-active');
                likesSpan.innerText = currentLikes + 1;
            } else {
                btn.classList.remove('like-btn-active');
                likesSpan.innerText = currentLikes - 1;
            }
            showToast('Erreur lors du like', false, 'alert-circle');
        }
        btn.disabled = false;
        setTimeout(() => initLucide(), 100);
    })
    .catch((error) => {
        console.error('Erreur:', error);
        // Annuler les changements en cas d'erreur
        if (isLiked) {
            btn.classList.add('like-btn-active');
            likesSpan.innerText = currentLikes + 1;
        } else {
            btn.classList.remove('like-btn-active');
            likesSpan.innerText = currentLikes - 1;
        }
        btn.disabled = false;
        showToast('Erreur lors du like', false, 'alert-circle');
    });
}

function submitComment(form, postId) {
    const content = form.querySelector('input[name="content"]').value;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    if (!content.trim()) return;
    
    submitBtn.innerHTML = '<div class="loader-orange" style="margin:0 auto;"></div>';
    submitBtn.disabled = true;
    
    fetch('/community/comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ post_id: postId, content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.comment) {
            const commentsList = document.getElementById(`comments-list-${postId}`);
            const isOwner = data.comment.is_owner;
            
            const newComment = `
                <div class="bg-white rounded-lg p-2.5 shadow-sm" id="comment-${data.comment.id}">
                    <div class="flex items-start gap-2">
                        <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 font-bold text-xs flex-shrink-0">${data.comment.user_initial}</div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                <span class="font-semibold text-gray-800 text-xs">${escapeHtml(data.comment.user_name)}</span>
                                <span class="text-xs text-gray-400">À l'instant</span>
                            </div>
                            <p class="comment-text text-gray-600 text-sm" id="comment-text-${data.comment.id}">${escapeHtml(content)}</p>
                            <div class="comment-edit-form" id="comment-edit-${data.comment.id}">
                                <input type="text" class="comment-edit-input" id="comment-edit-input-${data.comment.id}" value="${escapeHtml(content)}">
                                <button onclick="saveCommentEdit(${data.comment.id}, ${postId})" class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs">Sauvegarder</button>
                                <button onclick="cancelCommentEdit(${data.comment.id})" class="bg-gray-300 text-gray-700 px-3 py-1 rounded-full text-xs">Annuler</button>
                            </div>
                        </div>
                        ${isOwner ? `
                        <div class="flex items-center gap-1">
                            <button onclick="editCommentInline(${data.comment.id})" class="text-gray-400 hover:text-orange-500 transition" title="Modifier">
                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                            </button>
                            <button onclick="deleteComment(${data.comment.id}, ${postId})" class="text-gray-400 hover:text-red-500 transition" title="Supprimer">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            if (commentsList.innerHTML.includes('Aucun commentaire')) {
                commentsList.innerHTML = newComment;
            } else {
                commentsList.insertAdjacentHTML('afterbegin', newComment);
            }
            form.querySelector('input[name="content"]').value = '';
            const countSpan = document.getElementById(`comments-count-${postId}`);
            if (countSpan) {
                countSpan.innerText = parseInt(countSpan.innerText) + 1;
            }
            showToast('Commentaire ajouté !', true, 'message-circle');
            setTimeout(() => initLucide(), 100);
        }
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    })
    .catch(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        showToast('Erreur lors de l\'envoi', false, 'alert-circle');
    });
}

function sharePost(postId) {
    const postCard = document.querySelector(`.post-card:has(#likes-${postId})`);
    if (postCard) {
        const content = postCard.querySelector('p.text-gray-700')?.innerText || '';
        const shareText = `🍽️ ${content}\n\nPartagé depuis OuraTable !`;
        
        if (navigator.share) {
            navigator.share({
                title: 'OuraTable| L'assiette ouverte',
                text: shareText,
                url: window.location.href
            }).catch(() => {
                copyToClipboard(`${shareText}\n\n${window.location.href}`);
            });
        } else {
            copyToClipboard(`${shareText}\n\n${window.location.href}`);
        }
    } else {
        copyToClipboard(window.location.href);
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
    showToast('Lien copié dans le presse-papier !', true, 'clipboard');
}

//  GESTION DES COMMENTAIRES (INLINE EDIT)
function editCommentInline(commentId) {
    const commentText = document.getElementById(`comment-text-${commentId}`);
    const editForm = document.getElementById(`comment-edit-${commentId}`);
    
    if (commentText && editForm) {
        commentText.classList.add('hidden');
        editForm.classList.add('active');
        const input = document.getElementById(`comment-edit-input-${commentId}`);
        if (input) input.focus();
    }
}

function cancelCommentEdit(commentId) {
    const commentText = document.getElementById(`comment-text-${commentId}`);
    const editForm = document.getElementById(`comment-edit-${commentId}`);
    
    if (commentText && editForm) {
        commentText.classList.remove('hidden');
        editForm.classList.remove('active');
    }
}

function saveCommentEdit(commentId, postId) {
    const input = document.getElementById(`comment-edit-input-${commentId}`);
    const newContent = input.value.trim();
    
    if (!newContent) {
        showToast('Le commentaire ne peut pas être vide', false, 'alert-circle');
        return;
    }
    
    fetch(`/community/comment/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ content: newContent })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const commentText = document.getElementById(`comment-text-${commentId}`);
            if (commentText) {
                commentText.textContent = newContent;
            }
            cancelCommentEdit(commentId);
            showToast('Commentaire modifié avec succès !', true, 'edit-2');
        } else {
            showToast('Erreur lors de la modification', false, 'alert-circle');
        }
    })
    .catch(() => showToast('Erreur lors de la modification', false, 'alert-circle'));
}

function deleteComment(commentId, postId) {
    customConfirm('Voulez-vous vraiment supprimer ce commentaire ?', () => {
        fetch(`/community/comment/${commentId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
                const countSpan = document.getElementById(`comments-count-${postId}`);
                if (countSpan) {
                    countSpan.innerText = parseInt(countSpan.innerText) - 1;
                }
                showToast('Commentaire supprimé', true, 'trash-2');
            } else {
                showToast('Erreur lors de la suppression', false, 'alert-circle');
            }
        })
        .catch(() => showToast('Erreur lors de la suppression', false, 'alert-circle'));
    });
}

function editPostPrompt(postId, content, imageUrl, postType) {
    editingPostId = postId;
    editingPostHasImage = !!imageUrl;
    document.getElementById('editPostContent').value = content;
    const imageContainer = document.getElementById('editImageContainer');
    const removeBtn = document.getElementById('editRemoveImageBtn');
    
    if (imageUrl) {
        imageContainer.innerHTML = `<img src="${imageUrl}" class="rounded-xl max-h-40 w-full object-cover mb-2" id="editPreviewImage">`;
        removeBtn.classList.remove('hidden');
    } else {
        imageContainer.innerHTML = '';
        removeBtn.classList.add('hidden');
    }
    document.getElementById('editPostImageInput').value = '';
    document.getElementById('editPostModal').style.display = 'flex';
    setTimeout(initLucide, 100);
}

function previewEditImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editImageContainer').innerHTML = `<img src="${e.target.result}" class="rounded-xl max-h-40 w-full object-cover mb-2" id="editPreviewImage">`;
            document.getElementById('editRemoveImageBtn').classList.remove('hidden');
            editingPostHasImage = true;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeEditImage() {
    document.getElementById('editImageContainer').innerHTML = '';
    document.getElementById('editRemoveImageBtn').classList.add('hidden');
    document.getElementById('editPostImageInput').value = '';
    editingPostHasImage = false;
}

function closeEditModal() {
    document.getElementById('editPostModal').style.display = 'none';
    editingPostId = null;
}

function saveEditPost() {
    const newContent = document.getElementById('editPostContent').value.trim();
    const imageFile = document.getElementById('editPostImageInput').files[0];
    
    if (!newContent) {
        showToast('Le contenu ne peut pas être vide', false, 'alert-circle');
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('content', newContent);
    if (imageFile) {
        formData.append('image', imageFile);
    } else if (!editingPostHasImage) {
        formData.append('remove_image', '1');
    }
    formData.append('_method', 'PUT');
    
    fetch(`/community/post/${editingPostId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            showToast('Publication modifiée avec succès !', true, 'edit-2');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur lors de la modification', false, 'alert-circle');
        }
    })
    .catch(() => showToast('Erreur lors de la modification', false, 'alert-circle'));
}

function confirmDeletePost(postId) {
    customConfirm('Voulez-vous vraiment supprimer cette publication ?', () => deletePost(postId));
}

function deletePost(postId) {
    fetch(`/community/post/${postId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Publication supprimée', true, 'trash-2');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur lors de la suppression', false, 'alert-circle');
        }
    })
    .catch(() => showToast('Erreur lors de la suppression', false, 'alert-circle'));
}

//  DÉFI IA 
function previewChallengeImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('challengeImagePreviewImg').src = e.target.result;
            document.getElementById('challengeImagePreviewContainer').style.display = 'inline-block';
            document.getElementById('challengeImageUploadArea').style.display = 'none';
            currentChallengeImageFile = input.files[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(input.files[0]);
            document.getElementById('challengeImageFile').files = dataTransfer.files;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeChallengeImage() {
    document.getElementById('challengeImagePreviewContainer').style.display = 'none';
    document.getElementById('challengeImageUploadArea').style.display = 'block';
    document.getElementById('challengeImageInput').value = '';
    document.getElementById('challengeImageFile').value = '';
    currentChallengeImageFile = null;
}

function checkChallengeContent(content, ingredients) {
    if (!content || content.trim().length < 20) {
        return { valid: false, message: "Veuillez décrire votre création en détail (minimum 20 caractères)" };
    }
    if (ingredients && ingredients.length > 0) {
        const missingIngredients = [];
        for (let ing of ingredients) {
            if (!content.toLowerCase().includes(ing.toLowerCase())) {
                missingIngredients.push(ing);
            }
        }
        if (missingIngredients.length > 0 && missingIngredients.length === ingredients.length) {
            return { valid: false, message: `Comment avez-vous utilisé les ingrédients ? (${missingIngredients.join(', ')})` };
        } else if (missingIngredients.length > 0) {
            return { valid: true, warning: `Certains ingrédients ne sont pas mentionnés : ${missingIngredients.join(', ')}` };
        }
    }
    return { valid: true };
}

function openChallengePostModal(ingredients) {
    currentIngredients = ingredients || [];
    const app = getAlpineApp();
    if (app) {
        app.showPostModal = true;
        setTimeout(() => {
            const warning = document.getElementById('ingredientsWarning');
            if (warning) warning.classList.add('hidden');
            removeChallengeImage();
            document.getElementById('challengeContent').value = '';
        }, 100);
    }
}

function submitChallengePost() {
    const content = document.getElementById('challengeContent').value.trim();
    const warningDiv = document.getElementById('ingredientsWarning');
    
    const check = checkChallengeContent(content, currentIngredients);
    
    if (!check.valid) {
        showToast(check.message, false, 'alert-circle');
        if (warningDiv) {
            warningDiv.classList.remove('hidden');
            warningDiv.innerHTML = `<i data-lucide="alert-circle" class="w-3 h-3"></i> ${check.message}`;
            setTimeout(() => initLucide(), 100);
        }
        return;
    }
    
    if (check.warning) {
        customConfirm(check.warning + "\n\nPublier quand même ?", () => sendChallengePost());
        return;
    }
    
    sendChallengePost();
}

function sendChallengePost() {
    const content = document.getElementById('challengeContent').value.trim();
    const submitBtn = document.getElementById('challengeSubmitBtn');
    const originalText = submitBtn.innerHTML;
    showLoader(submitBtn, originalText);
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('content', content);
    formData.append('type', 'defi');
    if (currentChallengeImageFile) {
        formData.append('image', currentChallengeImageFile);
    }
    
    fetch('/community/post', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const app = getAlpineApp();
            if (app) app.showPostModal = false;
            showToast('Félicitations ! Participation au défi enregistrée !', true, 'trophy');
            setTimeout(() => location.reload(), 2000);
        } else {
            showToast('Erreur lors de la publication', false, 'alert-circle');
            hideLoader(submitBtn, originalText);
        }
    })
    .catch(() => {
        showToast('Erreur lors de la publication', false, 'alert-circle');
        hideLoader(submitBtn, originalText);
    });
}

//  GROUPES 
function toggleGroupMenu(event, groupId) {
    event.stopPropagation();
    const menu = document.getElementById(`groupMenu-${groupId}`);
    if (activeGroupMenu && activeGroupMenu !== menu) {
        activeGroupMenu.classList.add('hidden');
    }
    if (menu) {
        const btn = event.currentTarget;
        const rect = btn.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const menuHeight = 160;
        
        menu.classList.remove('group-menu-top', 'group-menu-bottom');
        if (spaceBelow < menuHeight) {
            menu.classList.add('group-menu-top');
        } else {
            menu.classList.add('group-menu-bottom');
        }
        
        menu.classList.toggle('hidden');
        activeGroupMenu = menu.classList.contains('hidden') ? null : menu;
    }
}

function viewGroupDetails(groupId, name, description, memberCount, category, isMember) {
    const app = getAlpineApp();
    if (app) {
        app.selectedGroup = { id: groupId, name, description, member_count: memberCount, category };
        app.showGroupDetailModal = true;
        app.groupMembers = [];
        
        fetch(`/community/group-members/${groupId}`)
            .then(response => response.json())
            .then(members => { app.groupMembers = members; setTimeout(initLucide, 100); })
            .catch(() => { app.groupMembers = []; setTimeout(initLucide, 100); });
        
        const actionBtn = document.getElementById('groupActionBtn');
        if (actionBtn) {
            if (isMember === true || isMember === 1) {
                actionBtn.innerHTML = '<i data-lucide="log-out" class="w-4 h-4"></i> Quitter';
                actionBtn.onclick = () => {
                    fetch(`/community/leave-group/${groupId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Vous avez quitté le groupe', true, 'log-out');
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
                };
            } else {
                actionBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4"></i> Rejoindre';
                actionBtn.onclick = () => {
                    fetch(`/community/join-group/${groupId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Vous avez rejoint le groupe !', true, 'log-in');
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
                };
            }
        }
        setTimeout(initLucide, 100);
    }
}

function joinGroup(groupId) {
    fetch(`/community/join-group/${groupId}`, { 
        method: 'POST', 
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Vous avez rejoint le groupe !', true, 'log-in');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur lors de l\'inscription', false, 'alert-circle');
        }
    })
    .catch(() => showToast('Erreur lors de l\'inscription', false, 'alert-circle'));
}

function leaveGroup(groupId) {
    customConfirm('Quitter ce groupe ?', () => {
        fetch(`/community/leave-group/${groupId}`, { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Vous avez quitté le groupe', true, 'log-out');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur lors du départ', false, 'alert-circle');
            }
        })
        .catch(() => showToast('Erreur lors du départ', false, 'alert-circle'));
    });
}

//  ALPINE JS 
function app() {
    return {
        activeFilter: 'all',
        showPostModal: false,
        showMessageUserModal: false,
        showGroupDetailModal: false,
        showStatsModal: false,
        selectedGroup: null,
        groupMembers: [],
        usersList: [],
        searchUser: '',
        filteredUsers: [],
        statsTitle: '',
        statsValue: 0,
        statsDescription: '',
        statsIcon: '',
        
        init() {
            initLucide();
            alpineApp = this;
            document.getElementById('typeRealisation').classList.add('selected');
            this.fetchUsers();
        },
        
        setFilter(filter) {
            this.activeFilter = filter;
            const filterName = filter === 'all' ? 'Tous' : filter === 'realisation' ? 'Réalisations' : filter === 'question' ? 'Questions' : 'Défis';
            showToast(`Filtre: ${filterName}`, true, 'filter');
        },
        
        async fetchUsers() {
            try {
                const response = await fetch('/community/users');
                this.usersList = await response.json();
                this.filteredUsers = this.usersList;
            } catch(e) { console.error(e); }
        },
        
        filterUsers() {
            if (!this.searchUser) {
                this.filteredUsers = this.usersList;
            } else {
                this.filteredUsers = this.usersList.filter(u => 
                    u.name.toLowerCase().includes(this.searchUser.toLowerCase())
                );
            }
        },
        
        startConversationWithUser(userId, userName) {
            this.showMessageUserModal = false;
            this.searchUser = '';
            startConversationWith(userId, userName);
        }
    };
}

//  MESSAGERIE 
let messengerState = {
    isOpen: false,
    currentTab: 'conversations',
    currentView: 'list',
    currentConversation: null,
    currentChatType: null,
    currentGroupId: null,
    conversations: @json($conversations ?? []),
    groups: @json($groups ?? []),
    messages: [],
    unreadCount: {{ $unreadCount ?? 0 }},
    pollingInterval: null
};

function toggleMessenger() {
    const window = document.querySelector('.messenger-window');
    messengerState.isOpen = !messengerState.isOpen;
    if (messengerState.isOpen) {
        window.style.display = 'flex';
        refreshConversations();
        if (!messengerState.pollingInterval) startPolling();
    } else {
        window.style.display = 'none';
        if (messengerState.pollingInterval) {
            clearInterval(messengerState.pollingInterval);
            messengerState.pollingInterval = null;
        }
    }
}

function switchTab(tab) {
    messengerState.currentTab = tab;
    document.querySelectorAll('.messenger-tab').forEach((el, i) => {
        if ((tab === 'conversations' && i === 0) || (tab === 'groups' && i === 1)) el.classList.add('active');
        else el.classList.remove('active');
    });
    renderList();
}

function startPolling() {
    messengerState.pollingInterval = setInterval(() => {
        if (messengerState.isOpen && messengerState.currentView === 'list') refreshConversations();
        if (messengerState.currentConversation && messengerState.currentView === 'chat') loadMessages(messengerState.currentConversation.id, true);
    }, 3000);
}

async function refreshConversations() {
    try {
        const response = await fetch('/community/conversations');
        const conversations = await response.json();
        messengerState.conversations = conversations;
        messengerState.unreadCount = conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
        if (messengerState.currentTab === 'conversations') renderList();
        updateUnreadBadge();
    } catch(e) { console.error(e); }
}

async function loadMessages(conversationId, isPolling = false) {
    try {
        const response = await fetch(`/community/messages/${conversationId}`);
        const messages = await response.json();
        messengerState.messages = messages;
        if (!isPolling) { renderMessages(); scrollToBottom(); }
        else {
            const container = document.querySelector('.messages-container');
            const oldHeight = container?.scrollHeight || 0;
            renderMessages();
            if ((container?.scrollHeight || 0) > oldHeight) scrollToBottom();
        }
    } catch(e) { console.error(e); }
}

async function sendMessage() {
    const input = document.querySelector('.message-input');
    const content = input.value.trim();
    if (!content) return;
    input.value = '';
    input.disabled = true;
    try {
        let url = '/community/send-message';
        let body = { conversation_id: messengerState.currentConversation.id, content: content };
        if (messengerState.currentChatType === 'group') {
            url = '/community/send-group-message';
            body = { group_id: messengerState.currentGroupId, content: content };
        }
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(body)
        });
        const message = await response.json();
        messengerState.messages.push(message);
        renderMessages();
        scrollToBottom();
        await refreshConversations();
    } catch(e) { console.error(e); input.value = content; }
    finally { input.disabled = false; input.focus(); }
}

function startConversationWith(userId, userName) {
    fetch('/community/start-conversation', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (!messengerState.isOpen) toggleMessenger();
        setTimeout(() => openConversation(data.conversation_id, data.user.id, userName, false, null), 300);
    })
    .catch(e => console.error(e));
}

function openGroupConversation(groupId, groupName) {
    fetch(`/community/start-group-conversation/${groupId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        if (!messengerState.isOpen) toggleMessenger();
        setTimeout(() => openConversation(data.conversation_id, null, groupName, true, groupId), 300);
    })
    .catch(e => console.error(e));
}

async function openConversation(conversationId, userId, name, isGroup, groupId) {
    messengerState.currentView = 'chat';
    messengerState.currentConversation = { id: conversationId };
    messengerState.currentChatType = isGroup ? 'group' : 'private';
    messengerState.currentGroupId = groupId;
    await loadMessages(conversationId);
    if (!isGroup) await fetch(`/community/mark-as-read/${conversationId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    await refreshConversations();
    document.querySelector('.messenger-list').style.display = 'none';
    document.querySelector('.chat-area').style.display = 'flex';
    document.getElementById('chat-user-name').textContent = name;
    document.getElementById('chat-user-initial').textContent = name.charAt(0).toUpperCase();
    setTimeout(() => document.querySelector('.message-input')?.focus(), 100);
    setTimeout(initLucide, 100);
}

function closeConversation() {
    messengerState.currentView = 'list';
    messengerState.currentConversation = null;
    messengerState.currentChatType = null;
    messengerState.currentGroupId = null;
    document.querySelector('.messenger-list').style.display = 'block';
    document.querySelector('.chat-area').style.display = 'none';
    refreshConversations();
}

function renderList() {
    const container = document.querySelector('.messenger-list');
    if (!container) return;
    
    if (messengerState.currentTab === 'conversations') {
        if (messengerState.conversations.length === 0) { container.innerHTML = '<div class="empty-messages"><i data-lucide="message-circle" class="w-10 h-10 mx-auto mb-2 opacity-50"></i><p>Aucune conversation</p></div>'; setTimeout(initLucide, 100); return; }
        container.innerHTML = messengerState.conversations.map(conv => {
            if (conv.type === 'group') {
                return `<div class="conversation-item" onclick="openGroupConversation(${conv.group_id}, '${escapeHtml(conv.name)}')">
                            <div class="conversation-avatar">${escapeHtml(conv.name.substring(0,2).toUpperCase())}</div>
                            <div class="conversation-info">
                                <div class="conversation-name">${escapeHtml(conv.name)}</div>
                                <div class="conversation-last-message">${escapeHtml(conv.last_message || 'Aucun message')}</div>
                            </div>
                            <div><div style="font-size:10px;color:#999;">${conv.member_count || 0} <i data-lucide="users" class="w-2.5 h-2.5 inline"></i></div></div>
                            <div class="group-menu-btn">
                                <button onclick="event.stopPropagation();toggleGroupMenu(event, ${conv.group_id})" class="three-dots-menu">
                                    <i data-lucide="more-vertical" class="w-3.5 h-3.5 text-gray-400"></i>
                                </button>
                                <div id="groupMenu-${conv.group_id}" class="hidden group-menu">
                                    <button onclick="viewGroupDetails(${conv.group_id}, '${escapeHtml(conv.name)}', '${escapeHtml(conv.description)}', ${conv.member_count}, '${escapeHtml(conv.category)}', ${conv.is_member})" class="group-menu-item">
                                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Détails
                                    </button>
                                    ${conv.is_member ? 
                                        `<button onclick="leaveGroup(${conv.group_id})" class="group-menu-item group-menu-item-danger">
                                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Quitter
                                        </button>` :
                                        `<button onclick="joinGroup(${conv.group_id})" class="group-menu-item group-menu-item-success">
                                            <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Rejoindre
                                        </button>`
                                    }
                                </div>
                            </div>
                            ${conv.unread_count > 0 ? `<div class="unread-badge">${conv.unread_count > 99 ? '99+' : conv.unread_count}</div>` : ''}
                        </div>`;
            } else {
                return `<div class="conversation-item" onclick="openConversation(${conv.id}, ${conv.user_id}, '${escapeHtml(conv.name)}', false, null)">
                            <div class="conversation-avatar">${escapeHtml(conv.avatar || conv.name.substring(0,2).toUpperCase())}</div>
                            <div class="conversation-info">
                                <div class="conversation-name">${escapeHtml(conv.name)}</div>
                                <div class="conversation-last-message">${escapeHtml(conv.last_message || 'Aucun message')}</div>
                            </div>
                            <div>${conv.unread_count > 0 ? `<div class="unread-badge">${conv.unread_count > 99 ? '99+' : conv.unread_count}</div>` : ''}</div>
                        </div>`;
            }
        }).join('');
        setTimeout(initLucide, 100);
    } else {
        if (messengerState.groups.length === 0) { container.innerHTML = '<div class="empty-messages"><i data-lucide="users" class="w-10 h-10 mx-auto mb-2 opacity-50"></i><p>Aucun groupe disponible</p></div>'; setTimeout(initLucide, 100); return; }
        container.innerHTML = messengerState.groups.map(group => `
            <div class="conversation-item" onclick="viewGroupDetails(${group.id}, '${escapeHtml(group.name)}', '${escapeHtml(group.description)}', ${group.member_count}, '${escapeHtml(group.category)}', ${group.is_member})">
                <div class="conversation-avatar">${escapeHtml(group.name.substring(0,2).toUpperCase())}</div>
                <div class="conversation-info">
                    <div class="conversation-name">${escapeHtml(group.name)}</div>
                    <div class="conversation-last-message">${escapeHtml(group.description ? group.description.substring(0, 45) : '')}</div>
                </div>
                <div class="text-xs text-gray-400">${group.member_count} <i data-lucide="users" class="w-2.5 h-2.5 inline"></i></div>
                <div class="group-menu-btn">
                    <button onclick="event.stopPropagation();toggleGroupMenu(event, ${group.id})" class="three-dots-menu">
                        <i data-lucide="more-vertical" class="w-3.5 h-3.5 text-gray-400"></i>
                    </button>
                    <div id="groupMenu-${group.id}" class="hidden group-menu">
                        <button onclick="viewGroupDetails(${group.id}, '${escapeHtml(group.name)}', '${escapeHtml(group.description)}', ${group.member_count}, '${escapeHtml(group.category)}', ${group.is_member})" class="group-menu-item">
                            <i data-lucide="info" class="w-3.5 h-3.5"></i> Détails
                        </button>
                        ${group.is_member ? 
                            `<button onclick="leaveGroup(${group.id})" class="group-menu-item group-menu-item-danger">
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Quitter
                            </button>` :
                            `<button onclick="joinGroup(${group.id})" class="group-menu-item group-menu-item-success">
                                <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Rejoindre
                            </button>`
                        }
                    </div>
                </div>
            </div>
        `).join('');
        setTimeout(initLucide, 100);
    }
}

function renderMessages() {
    const container = document.querySelector('.messages-container');
    if (!container) return;
    if (messengerState.messages.length === 0) { container.innerHTML = '<div class="empty-messages"><i data-lucide="message-circle" class="w-10 h-10 mx-auto mb-2 opacity-50"></i><p>Aucun message</p><p class="text-xs">Soyez le premier à envoyer un message !</p></div>'; setTimeout(initLucide, 100); return; }
    container.innerHTML = messengerState.messages.map(msg => `
        <div class="message-bubble ${msg.is_own ? 'message-own' : 'message-other'}">
            ${!msg.is_own && !msg.is_group ? `<div style="font-size:10px;margin-bottom:5px;color:#999;display:flex;align-items:center;gap:4px;"><i data-lucide="user" class="w-2.5 h-2.5"></i> ${escapeHtml(msg.user_name)}</div>` : ''}
            <div>${escapeHtml(msg.content)}</div>
            <div class="message-time">${msg.time}</div>
        </div>
    `).join('');
    setTimeout(initLucide, 100);
}

function scrollToBottom() { const container = document.querySelector('.messages-container'); if (container) container.scrollTop = container.scrollHeight; }
function updateUnreadBadge() { const badge = document.querySelector('.messenger-badge'); if (badge) badge.style.display = messengerState.unreadCount > 0 ? 'flex' : 'none'; if (badge && messengerState.unreadCount > 0) badge.textContent = messengerState.unreadCount > 99 ? '99+' : messengerState.unreadCount; }

function openNewConversationInMessenger() {
    const app = getAlpineApp();
    if (app) {
        app.showMessageUserModal = true;
        app.fetchUsers();
    }
}

function createMessengerUI() {
    const html = `
        <div class="messenger-toggle" onclick="toggleMessenger()">
            <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
            <div class="messenger-badge" style="display: none;"></div>
        </div>
        <div class="messenger-window" style="display: none;">
            <div class="messenger-header">
                <span><i data-lucide="messages-square" class="w-4 h-4 inline mr-2"></i>Messages</span>
                <i data-lucide="x" class="w-4 h-4 cursor-pointer" onclick="toggleMessenger()"></i>
            </div>
            <div class="messenger-tabs">
                <div class="messenger-tab active" onclick="switchTab('conversations')"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i> Conversations</div>
                <div class="messenger-tab" onclick="switchTab('groups')"><i data-lucide="users" class="w-3.5 h-3.5"></i> Groupes</div>
            </div>
            <div class="messenger-list custom-scrollbar"></div>
            <div class="chat-area" style="display: none; flex-direction: column; height: 100%;">
                <div class="chat-header">
                    <div class="back-btn" onclick="closeConversation()"><i data-lucide="arrow-left" class="w-4 h-4"></i></div>
                    <div class="conversation-avatar w-9 h-9 text-sm"><span id="chat-user-initial"></span></div>
                    <div><strong id="chat-user-name"></strong></div>
                </div>
                <div class="messages-container custom-scrollbar"></div>
                <div class="message-input-area">
                    <input type="text" class="message-input" placeholder="Écrivez votre message..." onkeypress="if(event.key==='Enter') sendMessage();">
                    <button class="send-btn" onclick="sendMessage()"><i data-lucide="send" class="w-3.5 h-3.5"></i> Envoyer</button>
                </div>
            </div>
            <div class="messenger-footer">
                <div class="new-conv-footer-btn" onclick="openNewConversationInMessenger()">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
    setTimeout(() => { initLucide(); refreshConversations(); }, 100);
}

//  INITIALISATION 
document.addEventListener('DOMContentLoaded', function() {
    createMessengerUI();
    initLucide();
    
    document.querySelectorAll('textarea').forEach(ta => {
        ta.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
});
</script>
@endsection