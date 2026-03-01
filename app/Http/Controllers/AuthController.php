<?php

namespace App\Http\Controllers;

// 1. IMPORTATIONS OBLIGATOIRES
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// 2. DÉCLARATION DE LA CLASSE
class AuthController extends Controller
{
    // INSCRIPTION
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', 
        ], [
            'email.unique' => 'Cet email est déjà pris.',
            'phone.unique' => 'Ce numéro est déjà utilisé.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.'
        ]);

        // Utilisation du modèle User (vérifie qu'il s'appelle User et pas Utilisateur)
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirection vers le formulaire de connexion (qui est le panneau par défaut)
        return redirect()->route('login')->with('success', 'Inscription réussie ! Connectez-vous.');
    }

    // CONNEXION
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // DEBUG : Décommente la ligne suivante pour voir ce que Laravel reçoit
    // dd($credentials); 

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    // Si on arrive ici, c'est que Auth::attempt a renvoyé FALSE
    return back()->withErrors([
        'email' => 'Identifiants incorrects ou compte non reconnu.',
    ])->withInput();
}
}