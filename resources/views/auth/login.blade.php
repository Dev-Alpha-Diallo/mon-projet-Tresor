@extends('layouts.app')

@section('title', 'Connexion - Trésorerie')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Card de login -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Entête -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-primary to-primary-dark rounded-lg mb-4">
                    <span class="text-white text-xl font-bold">₣</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Trésorerie</h1>
                <p class="text-gray-500 mt-2">Amicale des Étudiants</p>
            </div>

            <!-- Messages d'info -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 text-sm font-semibold">Connexion échouée</p>
                    @foreach ($errors->all() as $error)
                        <p class="text-red-700 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Formulaire -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Adresse e-mail
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                        placeholder="votre.email@example.com"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Bouton submit -->
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition"
                >
                    Se connecter
                </button>
            </form>

            <!-- Infos pour développement local -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-900 font-semibold mb-2">🔐 Accès local</p>
                    <p class="text-sm text-blue-800">Pour vous connecter, utilisez :</p>
                    <div class="flex flex-col gap-2 mt-2">
                        <div>
                            <span class="font-semibold text-blue-700">Admin :</span>
                            <span class="text-xs font-mono">admin@treso.local</span>
                            <span class="text-xs font-mono ml-2">Mdp: password</span>
                        </div>
                        <div>
                            <span class="font-semibold text-blue-700">Client :</span>
                            <span class="text-xs font-mono">client@treso.local</span>
                            <span class="text-xs font-mono ml-2">Mdp: motdepasse</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
