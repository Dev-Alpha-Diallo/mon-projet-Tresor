@extends('layouts.app')

@section('title', 'Nouvelle Facture')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('factures.index') }}" class="text-primary hover:text-primary-dark flex items-center space-x-2">
            <span>←</span>
            <span>Retour aux factures</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">📄 Nouvelle Facture</h1>
        <p class="text-gray-600 mt-1">Facture d’eau, d’électricité ou de réparation pour une maison</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('factures.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Maison -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Maison concernée *
                    </label>
                    <select name="maison_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Sélectionner une maison</option>
                        @foreach($maisons as $maison)
                            <option value="{{ $maison->id }}"
                                {{ old('maison_id') == $maison->id ? 'selected' : '' }}>
                                {{ $maison->nom }} – {{ $maison->adresse }}
                            </option>
                        @endforeach
                    </select>
                    @error('maison_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de facture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type de facture *
                    </label>
                    <select name="type" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Sélectionner</option>
                        <option value="eau" {{ old('type') == 'eau' ? 'selected' : '' }}>💧 Eau</option>
                        <option value="electricite" {{ old('type') == 'electricite' ? 'selected' : '' }}>⚡ Électricité</option>
                        <option value="reparation" {{ old('type') == 'reparation' ? 'selected' : '' }}>🛠 Réparation</option>
                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>📦 Autre</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date de paiement *
                    </label>
                    <input type="date" name="date_paiement" required
                           value="{{ old('date_paiement', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('date_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Montant (FCFA) *
                    </label>
                    <div class="relative">
                        <input type="number" name="montant" required min="0"
                               value="{{ old('montant') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <span class="absolute left-3 top-3 text-gray-500">F</span>
                    </div>
                    @error('montant')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Numéro de facture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Numéro de facture *
                    </label>
                    <input type="text" name="numero_facture" required
                           value="{{ old('numero_facture') }}"
                           placeholder="Ex: SENELEC-2026-001"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('numero_facture')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Détails de la facture...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('factures.index') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-primary to-primary-dark text-white px-6 py-3 rounded-lg font-medium hover:shadow-md transition flex items-center space-x-2">
                    <span>💾</span>
                    <span>Créer la facture</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
