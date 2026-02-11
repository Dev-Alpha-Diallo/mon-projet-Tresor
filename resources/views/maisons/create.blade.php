@extends('layouts.app')

@section('title', 'Ajouter une maison')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">🏠 Ajouter une maison</h1>
                <p class="text-gray-500 mt-1">Nouvelle propriété locative</p>
            </div>
            <a href="{{ route('maisons.index') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                ← Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('maisons.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Colonne gauche -->
                <div class="space-y-4">
                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la maison</label>
                        <input type="text" name="nom" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse complète</label>
                        <textarea name="adresse" rows="3"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               required></textarea>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-4">
                    <!-- Bailleur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bailleur</label>
                        <select name="bailleur_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                required>
                            <option value="">Sélectionner un bailleur</option>
                            @foreach($bailleurs as $bailleur)
                                <option value="{{ $bailleur->id }}">{{ $bailleur->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Loyer total -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Loyer total mensuel (FCFA)</label>
                        <input type="number" name="loyer_total_mensuel" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               required min="0" step="1000">
                    </div>

                    <!-- Nombre de chambres -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de chambres</label>
                        <input type="number" name="nombre_chambres" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               min="1" value="1">
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                <a href="{{ route('maisons.index') }}" 
                   class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark">
                    Enregistrer la maison
                </button>
            </div>
        </form>
    </div>
</div>
@endsection