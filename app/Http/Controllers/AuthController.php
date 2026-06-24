<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // INSCRIPTION
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', 
        ], [
            'email.unique' => 'email_exists',
            'phone.unique' => 'phone_exists',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Envoi de l'email de vérification
        try {
            event(new Registered($user));
            Log::info('Email de vérification envoyé à: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email: ' . $e->getMessage());
        }

        // Redirection vers login avec modal de succès
        return redirect()->route('login')->with('register_success', true)
            ->with('user_email', $user->email)
            ->with('modal_title', 'Inscription réussie !')
            ->with('modal_message', 'Votre compte a été créé avec succès. Un email de vérification a été envoyé à ' . $user->email);
    }

    // CONNEXION
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Chercher l'utilisateur par son email
        $user = User::where('email', $credentials['email'])->first();

        // 2. Vérifier si l'utilisateur existe
        if (!$user) {
            return back()->withErrors([
                'email_missing' => true,
            ])->withInput($request->only('email'));
        }

        // 3. Vérifier si le compte est vérifié
        if (is_null($user->email_verified_at)) {
            return back()->with('email_not_verified', 'Veuillez vérifier votre email avant de vous connecter. Un lien de vérification a été envoyé à ' . $user->email);
        }

        // 4. Vérifier si le mot de passe est correct
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password_incorrect' => true,
            ])->withInput($request->only('email'));
        }

        // 5. Si tout est bon, on connecte l'utilisateur
        Auth::login($user);
        $request->session()->regenerate();

        // Connexion reussie : on affiche un message de bienvenue (modal stylise)
        // sur la page de login, puis redirection automatique vers la destination.
        if ($user->isAdmin()) {
            $destination = route('admin.dashboard');
            $message = 'Bienvenue, ' . $user->name . ' ! Votre tableau de bord vous attend.';
        } else {
            $destination = '/UserHome';
            $message = 'Heureux de vous revoir ' . $user->name . ' ! Votre couvert est mis.';
        }

        return redirect()->route('login')
            ->with('welcome_back', $message)
            ->with('redirect_to', $destination);
    }

    // DÉCONNEXION
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}