<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'client') {
            return redirect()->route('client.dashboard');
        }
        return view('client.auth.login');
    }
   
    
    

    public function login(Request $request)
    {
        $request->validate([
            'telephone' => ['required', 'string'],
            'password'  => ['required'],
        ]);

         // ── Bloquer après 5 tentatives ──────────────────────
    $key = 'login:' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 4)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->withErrors([
            'telephone' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
        ]);
    }

    // ── Vérifier les identifiants ───────────────────────
    $user = User::where('telephone', $request->telephone)
                ->where('role', 'client')
                ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        // Incrémenter le compteur de tentatives
        RateLimiter::hit($key, 60); // 60 secondes de blocage

        return back()->withErrors([
            'telephone' => 'Numéro de téléphone ou mot de passe incorrect.',
        ])->onlyInput('telephone');
    }

    // ── Connexion réussie → reset le compteur ───────────
    RateLimiter::clear($key);

    Auth::login($user, $request->boolean('remember'));
    $request->session()->regenerate();

    return redirect()->intended(route('client.dashboard'));

        // Trouver le user par téléphone
        $user = User::where('telephone', $request->telephone)
                    ->where('role', 'client')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'telephone' => 'Numéro de téléphone ou mot de passe incorrect.',
            ])->onlyInput('telephone');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'));
    }

   public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('client.auth.logout'); // ← afficher la page au lieu de redirect
    }
}