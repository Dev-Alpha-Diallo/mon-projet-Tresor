@extends('layouts.app')

@section('title', 'Modifier Facture #' . $facture->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('factures.show', $facture) }}" class="text-primary hover:text-primary-dark flex items-center space-x-2">
            <span>←</span>
            <span>Retour à la facture</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">
            ✏️ Modifier Facture #{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}
        </h1>
        <p class="text-gray-600 mt-1">Pour {{ $facture->etudiant->nom }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('factures.update', $facture) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Statut -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut de la facture</label>
                <div class="flex flex-wrap gap-3">
                    <label class="inline-flex items-center">
                        <input type="radio" name="statut" value="impayee" 
                               {{ $facture->statut == 'impayee' ? 'checked' : '' }}
                               class="text-primary focus:ring-primary">
                        <span class="ml-2 text-gray-700">Impayée</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="statut" value="partiel" 
                               {{ $facture->statut == 'partiel' ? 'checked' : '' }}
                               class="text-primary focus:ring-primary">
                        <span class="ml-2 text-gray-700">Partielle</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="statut" value="payee" 
                               {{ $facture->statut == 'payee' ? 'checked' : '' }}
                               class="text-primary focus:ring-primary">
                        <span class="ml-2 text-gray-700">Payée</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Étudiant (lecture seule) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Étudiant</label>
                    <div class="bg-gray-50 border border-gray-300 rounded-lg px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $facture->etudiant->nom }}</div>
                        <div class="text-sm text-gray-600">Chambre {{ $facture->etudiant->chambre }}</div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">L'étudiant ne peut pas être modifié</p>
                </div>

                <!-- Mois -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mois de la facture *</label>
                    <input type="month" name="mois" required
                           value="{{ old('mois', $facture->mois) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Date échéance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'échéance *</label>
                    <input type="date" name="date_echeance" required
                           value="{{ old('date_echeance', $facture->date_echeance) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA) *</label>
                    <div class="relative">
                        <input type="number" name="montant" required step="0.01"
                               value="{{ old('montant', $facture->montant) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <span class="absolute left-3 top-3 text-gray-500">F</span>
                    </div>
                </div>

                <!-- Type de facture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de facture</label>
                    <select name="type"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="loyer" {{ $facture->type == 'loyer' ? 'selected' : '' }}>Loyer</option>
                        <option value="eau" {{ $facture->type == 'eau' ? 'selected' : '' }}>Facture d'eau</option>
                        <option value="electricite" {{ $facture->type == 'electricite' ? 'selected' : '' }}>Facture d'électricité</option>
                        <option value="internet" {{ $facture->type == 'internet' ? 'selected' : '' }}>Internet</option>
                        <option value="divers" {{ $facture->type == 'divers' ? 'selected' : '' }}>Divers</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Détails supplémentaires...">{{ old('description', $facture->description) }}</textarea>
                </div>

                <!-- Remarques -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Remarques (interne)</label>
                    <textarea name="remarques" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Notes internes...">{{ old('remarques', $facture->remarques) }}</textarea>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <form action="{{ route('factures.destroy', $facture) }}" method="POST" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-red-600 hover:text-red-800 font-medium flex items-center space-x-2">
                        <span>🗑️</span>
                        <span>Supprimer la facture</span>
                    </button>
                </form>

                <div class="flex space-x-3">
                    <a href="{{ route('factures.show', $facture) }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-r from-primary to-primary-dark text-white px-6 py-3 rounded-lg font-medium hover:shadow-md transition flex items-center space-x-2">
                        <span>💾</span>
                        <span>Mettre à jour</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection