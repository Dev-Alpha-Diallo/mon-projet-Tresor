@extends('layouts.app')

@section('title', 'Ajouter un étudiant')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <!-- Titre -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-extrabold text-gray-800">
            Ajouter un étudiant
        </h1>
        <p class="text-gray-500 mt-2">
            Renseignez les informations de l’étudiant et sa période de location
        </p>
    </div>

    <!-- Carte formulaire -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('etudiants.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nom + Filière -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nom complet
                    </label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Filière
                    </label>
                    <input type="text" name="filiere" value="{{ old('filiere') }}" required
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('filiere')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Maison + Chambre -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Maison
                    </label>
                    <select name="maison_id" required
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Sélectionner une maison</option>
                        @foreach($maisons as $maison)
                            <option value="{{ $maison->id }}" {{ old('maison_id') == $maison->id ? 'selected' : '' }}>
                                {{ $maison->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('maison_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Chambre
                    </label>
                    <input type="text" name="chambre" value="{{ old('chambre') }}" required
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('chambre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Loyer + Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Loyer mensuel (FCFA)
                    </label>
                    <input type="number" name="loyer_mensuel" value="{{ old('loyer_mensuel') }}" required min="0"
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('loyer_mensuel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Date de début de location
                    </label>
                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" required
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <p class="text-xs text-gray-500 mt-1">
                        💡 Exemple : pour payer en mars le loyer de février → 01/02/2026
                    </p>
                    @error('date_debut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('etudiants.index') }}"
                   class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition">
                    Annuler
                </a>

                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold shadow-lg transition">
                    Enregistrer l’étudiant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
