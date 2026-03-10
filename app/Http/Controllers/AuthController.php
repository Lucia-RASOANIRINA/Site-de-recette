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
        // Redirection avec un message plus chaleureux
        return redirect()->route('login')->with('success', 'Bienvenue à notre table ! Votre place est prête, connectez-vous pour commencer la dégustation.');

    }

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
            'email' => 'Ce compte n\'existe pas à notre table.',
        ])->withInput($request->only('email'));
    }

    // 3. Vérifier si le mot de passe est correct
    if (!Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors([
            'password' => 'Le mot de passe est incorrect.',
        ])->withInput($request->only('email'));
    }

    // 4. Si tout est bon, on connecte l'utilisateur
    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->intended('/UserHome')
        ->with('success', 'Heureux de vous revoir ! Votre couvert est mis.');
}
}