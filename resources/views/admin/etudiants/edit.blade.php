@extends('layouts.app')

@section('title', 'Modifier un étudiant')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modifier un étudiant</h1>
        <p class="text-gray-500 mt-1">
            Mettre à jour les informations de l’étudiant
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.etudiants.update', $etudiant) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Grille 2 colonnes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet
                    </label>
                    <input type="text"
                        name="nom"
                        value="{{ old('nom', $etudiant->nom) }}"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex : Jean Dupont">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Filière -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Filière
                    </label>
                    <input type="text"
                        name="filiere"
                        value="{{ old('filiere', $etudiant->filiere) }}"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex : Informatique">
                    @error('filiere')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Numéro de téléphone
                        <span class="text-xs font-normal text-indigo-500 ml-1">
                            (identifiant de connexion espace étudiant)
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">+221</span>
                        <input type="tel"
                            name="telephone"
                            value="{{ old('telephone', $etudiant->telephone) }}"
                            class="w-full pl-14 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="77 123 45 67">
                    </div>
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @if(!$etudiant->user_id)
                        <p class="text-xs text-orange-500 mt-1">
                            ⚠️ Aucun compte associé — renseigner le téléphone créera automatiquement un compte (mot de passe : 123456789)
                        </p>
                    @else
                        <p class="text-xs text-green-500 mt-1">
                            ✅ Compte actif
                        </p>
                    @endif
                </div>

                <!-- Maison -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Maison
                    </label>
                    <select name="maison_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner une maison</option>
                        @foreach($maisons as $maison)
                            <option value="{{ $maison->id }}"
                                {{ old('maison_id', $etudiant->maison_id) == $maison->id ? 'selected' : '' }}>
                                {{ $maison->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('maison_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Chambre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Chambre
                    </label>
                    <input type="text"
                        name="chambre"
                        value="{{ old('chambre', $etudiant->chambre) }}"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex : B12">
                    @error('chambre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Loyer -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Loyer mensuel (FCFA)
                    </label>
                    <input type="number"
                        name="loyer_mensuel"
                        value="{{ old('loyer_mensuel', $etudiant->loyer_mensuel) }}"
                        required
                        min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex : 30000">
                    @error('loyer_mensuel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début de location
                    </label>
                    <input type="date"
                        name="date_debut"
                        value="{{ old('date_debut', optional($etudiant->date_debut)->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">
                        💡 Pour payer en mars le loyer de février, mettre : 01/02/2026
                    </p>
                    @error('date_debut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.etudiants.index') }}"
                    class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Annuler
                </a>

                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
