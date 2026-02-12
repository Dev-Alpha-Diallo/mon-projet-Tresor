@extends('layouts.app')

@section('title', 'Enregistrer un paiement')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Enregistrer un paiement</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('paiements.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Étudiant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Étudiant</label>
                    <select name="etudiant_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}" {{ old('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                                {{ $etudiant->nom }} - {{ $etudiant->maison->nom }} (Chambre {{ $etudiant->chambre }})
                            </option>
                        @endforeach
                    </select>
                    @error('etudiant_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="montant" value="{{ old('montant') }}" required min="0" step="1"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('montant')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ old('date_paiement', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('date_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Moyen de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Moyen de paiement</label>
                    <select name="moyen_paiement" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="especes" {{ old('moyen_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="mobile_money" {{ old('moyen_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="virement" {{ old('moyen_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                    </select>
                    @error('moyen_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remarque -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Remarque (optionnel)</label>
                    <textarea name="remarque" rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('remarque') }}</textarea>
                    @error('remarque')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="submit" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                    Enregistrer
                </button>
                <a href="{{ route('paiements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection