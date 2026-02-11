@extends('layouts.app')

@section('title', 'Modifier la maison')

@section('content')
<div class="p-4">
    <!-- En-tête -->
    <div class="mb-6">
        <a href="{{ route('maisons.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
            ← Retour à la liste
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">Modifier la maison</h1>
    </div>

    <!-- Carte du formulaire -->
    <div class="bg-white rounded-lg border shadow-sm p-4">
        <form action="{{ route('maisons.update', $maison) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom de la maison *
                    </label>
                    <input type="text" 
                           id="nom" 
                           name="nom" 
                           value="{{ old('nom', $maison->nom) }}"
                           required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adresse -->
                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse complète *
                    </label>
                    <textarea id="adresse" 
                              name="adresse" 
                              rows="2"
                              required
                              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('adresse', $maison->adresse) }}</textarea>
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bailleur -->
                <div>
                    <label for="bailleur_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Bailleur *
                    </label>
                    <select id="bailleur_id" 
                            name="bailleur_id" 
                            required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Sélectionnez un bailleur</option>
                        @foreach($bailleurs as $bailleur)
                            <option value="{{ $bailleur->id }}" 
                                    {{ old('bailleur_id', $maison->bailleur_id) == $bailleur->id ? 'selected' : '' }}>
                                {{ $bailleur->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('bailleur_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Loyer total mensuel -->
                <div>
                    <label for="loyer_total_mensuel" class="block text-sm font-medium text-gray-700 mb-1">
                        Loyer total mensuel (FCFA) *
                    </label>
                    <input type="number" 
                           id="loyer_total_mensuel" 
                           name="loyer_total_mensuel" 
                           value="{{ old('loyer_total_mensuel', $maison->loyer_total_mensuel) }}"
                           required
                           min="0"
                           step="1000"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('loyer_total_mensuel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations sur les étudiants -->
                @if($maison->etudiants->count() > 0)
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">
                        {{ $maison->etudiants->count() }} étudiant{{ $maison->etudiants->count() > 1 ? 's' : '' }} dans cette maison
                    </p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        @foreach($maison->etudiants as $etudiant)
                        <li>{{ $etudiant->nom }} - Chambre {{ $etudiant->chambre }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Boutons d'action -->
                <div class="pt-4 border-t flex justify-between">
                    <a href="{{ route('maisons.index') }}" 
                       class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <div class="flex gap-2">
                        <a href="{{ route('maisons.show', $maison) }}" 
                           class="px-4 py-2 border rounded-lg text-blue-600 hover:bg-blue-50 transition">
                            Voir détails
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection