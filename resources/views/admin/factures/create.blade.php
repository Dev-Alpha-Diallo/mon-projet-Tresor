@extends('layouts.app')
@section('title', 'Nouvelle Facture')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.factures.index') }}" 
               class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nouvelle facture</h1>
                <p class="text-sm text-gray-500 mt-1">Créez une nouvelle facture pour une maison</p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.factures.store') }}" method="POST" class="divide-y divide-gray-200">
            @csrf

            <!-- Section: Informations principales -->
            <div class="p-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                    Informations générales
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Maison -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Maison <span class="text-red-500">*</span>
                        </label>
                        <select name="maison_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white">
                            <option value="" class="text-gray-400">Sélectionnez une maison</option>
                            @foreach($maisons as $maison)
                                <option value="{{ $maison->id }}" {{ old('maison_id') == $maison->id ? 'selected' : '' }} class="py-2">
                                    {{ $maison->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Numéro facture -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de facture <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="numero_facture"
                                value="{{ old('numero_facture') }}" required
                                placeholder="FAC-2024-001"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Type de facture <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white">
                            <option value="" class="text-gray-400">Sélectionnez un type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }} class="py-2">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Montant -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Montant <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="montant"
                                value="{{ old('montant') }}" required min="0" step="100"
                                placeholder="0"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition pl-16">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-medium">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="statut" id="statut" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white appearance-none">
                                <option value="impayee" {{ old('statut','impayee') == 'impayee' ? 'selected' : '' }}>Impayée</option>
                                <option value="partiel" {{ old('statut') == 'partiel' ? 'selected' : '' }}>Paiement partiel</option>
                                <option value="payee" {{ old('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Dates -->
            <div class="p-8 bg-gray-50/50">
                <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                    Dates
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Date émission -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'émission <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="date_emission"
                                value="{{ old('date_emission', date('Y-m-d')) }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Date échéance -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'échéance <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="date_echeance"
                                value="{{ old('date_echeance') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Date paiement -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date de paiement
                        </label>
                        <div class="relative">
                            <input type="date" name="date_paiement"
                                id="date_paiement"
                                value="{{ old('date_paiement') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            À remplir uniquement si la facture est payée
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section: Informations complémentaires -->
            <div class="p-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                    Informations complémentaires
                </h2>

                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="4"
                            placeholder="Décrivez les détails de la facture (prestations, période concernée, etc.)"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old('description') }}</textarea>
                    </div>

                    <!-- Remarques internes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Remarques internes
                        </label>
                        <textarea name="remarques" rows="3"
                            placeholder="Ajoutez des notes pour usage interne (non visible par le client)"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old('remarques') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Footer: Actions -->
            <div class="px-8 py-6 bg-gray-50 flex justify-end gap-4">
                <a href="{{ route('admin.factures.index') }}"
                   class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Créer la facture
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const statut = document.getElementById('statut');
        const datePaiement = document.getElementById('date_paiement');

        function toggleDatePaiement() {
            if (statut.value === 'impayee') {
                datePaiement.value = '';
                datePaiement.disabled = true;
                datePaiement.classList.add('bg-gray-100', 'text-gray-500');
            } else {
                datePaiement.disabled = false;
                datePaiement.classList.remove('bg-gray-100', 'text-gray-500');
            }
        }

        // Initialisation
        toggleDatePaiement();
        
        // Écouter les changements
        statut.addEventListener('change', toggleDatePaiement);
    })();
</script>

<style>
    /* Styles supplémentaires pour l'apparence moderne */
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0.5;
        transition: opacity 0.2s;
        cursor: pointer;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
    
    select {
        background-image: none;
    }
</style>
@endsection