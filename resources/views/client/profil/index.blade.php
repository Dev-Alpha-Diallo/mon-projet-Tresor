@extends('layouts.client')
@section('title', 'Mon Profil')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Mon Profil</h1>
        <p class="text-gray-400 text-xs mt-0.5">Vos informations personnelles</p>
    </div>

    {{-- Avatar card --}}
    <div class="bg-gradient-to-r from-sky-400 to-cyan-500 rounded-2xl p-5 mb-5 text-white shadow-md">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-lg leading-tight">{{ $etudiant->nom }}</p>
                <p class="text-sky-100 text-sm">{{ $etudiant->filiere }}</p>
                <p class="text-sky-100 text-xs mt-0.5">
                    {{ $etudiant->maison->nom ?? 'N/A' }} — Chambre {{ $etudiant->chambre }}
                </p>
            </div>
        </div>
    </div>

    {{-- 2 colonnes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Colonne gauche : Informations --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50">
                <h2 class="font-bold text-gray-800 text-sm">Informations personnelles</h2>
            </div>
            <div class="divide-y divide-gray-50">

                <div class="px-5 py-3.5 flex items-center gap-3">
                    <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Nom complet</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $etudiant->nom }}</p>
                    </div>
                </div>

                <div class="px-5 py-3.5 flex items-center gap-3">
                    <div class="w-9 h-9 bg-cyan-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Téléphone</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $user->telephone }}</p>
                    </div>
                </div>

                <div class="px-5 py-3.5 flex items-center gap-3">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Filière</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $etudiant->filiere }}</p>
                    </div>
                </div>

                <div class="px-5 py-3.5 flex items-center gap-3">
                    <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Maison / Chambre</p>
                        <p class="font-semibold text-gray-800 text-sm">
                            {{ $etudiant->maison->nom ?? 'N/A' }} — Chambre {{ $etudiant->chambre }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Colonne droite : Mot de passe --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50">
                <h2 class="font-bold text-gray-800 text-sm">Changer le mot de passe</h2>
            </div>
            <div class="p-5">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('client.profil.password') }}" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Mot de passe actuel</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-sky-400 transition
                                   {{ $errors->has('current_password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nouveau mot de passe</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-sky-400 to-cyan-500 hover:from-sky-500 hover:to-cyan-600
                               text-white font-semibold py-2.5 rounded-xl transition shadow-md text-sm mt-2">
                        Mettre à jour
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection