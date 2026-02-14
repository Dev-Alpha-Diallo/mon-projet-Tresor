@extends('layouts.app')

@section('title', 'Facture #' . $facture->id)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- En-tête avec actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <a href="{{ route('admin.factures.index') }}" class="text-primary hover:text-primary-dark flex items-center space-x-2">
                <span>←</span>
                <span>Retour aux factures</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">
                Facture #{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}
            </h1>
            <div class="flex items-center space-x-3 mt-2">
                <span class="text-gray-600">Pour {{ $facture->etudiant->nom }}</span>
                <span class="text-gray-400">•</span>
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                    {{ \Carbon\Carbon::parse($facture->mois)->format('F Y') }}
                </span>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.factures.edit', $facture) }}" 
               class="bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition flex items-center space-x-2">
                <span>✏️</span>
                <span>Modifier</span>
            </a>
            <a href="{{ route('admin.factures.generate-pdf', $facture) }}" 
               target="_blank"
               class="bg-gradient-to-r from-primary to-primary-dark text-white px-4 py-2.5 rounded-lg font-medium hover:shadow-md transition flex items-center space-x-2">
                <span>📄</span>
                <span>Générer PDF</span>
            </a>
            @if($facture->statut != 'payee')
            <a href="{{ route('admin.paiements.create', ['facture_id' => $facture->id]) }}" 
               class="bg-green-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-green-700 transition flex items-center space-x-2">
                <span>💳</span>
                <span>Enregistrer paiement</span>
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche: Détails facture -->
        <div class="lg:col-span-2">
            <!-- Carte facture -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <!-- En-tête facture -->
                <div class="bg-gradient-to-r from-primary to-primary-dark p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white">FACTURE</h2>
                            <p class="text-primary-100 mt-1">N° FACT-{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-white text-lg font-bold">
                                @if($facture->statut == 'payee')
                                <span class="bg-green-500 text-white px-4 py-1.5 rounded-full text-sm">PAYÉE</span>
                                @elseif($facture->statut == 'partiel')
                                <span class="bg-yellow-500 text-white px-4 py-1.5 rounded-full text-sm">PARTIELLE</span>
                                @else
                                <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm">IMPAYÉE</span>
                                @endif
                            </div>
                            <p class="text-primary-100 mt-2">Échéance: {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Corps facture -->
                <div class="p-6">
                    <!-- Informations étudiant -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="font-medium text-gray-500 mb-3">ÉMIS POUR</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-bold text-lg">{{ $facture->etudiant->nom }}</p>
                                <p class="text-gray-600">Chambre {{ $facture->etudiant->chambre }}</p>
                                <p class="text-gray-600">{{ $facture->etudiant->email ?? 'Email non fourni' }}</p>
                                <p class="text-gray-600">{{ $facture->etudiant->telephone ?? 'Téléphone non fourni' }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-500 mb-3">DÉTAILS FACTURE</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Date création:</span>
                                    <span class="font-medium">{{ $facture->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Période:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($facture->mois)->format('F Y') }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium capitalize">{{ $facture->type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Référence:</span>
                                    <span class="font-mono font-medium">FACT-{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails du montant -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Description</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                @if($facture->type == 'loyer')
                                                Loyer mensuel - Chambre {{ $facture->etudiant->chambre }}
                                                @elseif($facture->type == 'eau')
                                                Facture d'eau - {{ \Carbon\Carbon::parse($facture->mois)->format('M Y') }}
                                                @elseif($facture->type == 'electricite')
                                                Facture d'électricité - {{ \Carbon\Carbon::parse($facture->mois)->format('M Y') }}
                                                @else
                                                {{ $facture->description }}
                                                @endif
                                            </div>
                                            @if($facture->description)
                                            <div class="text-sm text-gray-500 mt-1">{{ $facture->description }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-lg font-bold text-gray-900">
                                        {{ number_format($facture->montant, 0, ',', ' ') }} F
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">TOTAL</td>
                                    <td class="px-6 py-4 text-2xl font-bold text-primary">
                                        {{ number_format($facture->montant, 0, ',', ' ') }} F
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Description et remarques -->
                    @if($facture->remarques)
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="font-medium text-yellow-800 mb-2">📝 Remarques internes</h4>
                        <p class="text-yellow-700">{{ $facture->remarques }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Historique des paiements -->
            <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Historique des paiements</h3>
                
                @if($facture->paiements->count() > 0)
                <div class="space-y-4">
                    @foreach($facture->paiements as $paiement)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center space-x-3">
                                    <span class="font-medium text-gray-900">{{ $paiement->mode_paiement }}</span>
                                    <span class="text-sm text-gray-500">{{ $paiement->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($paiement->reference)
                                <div class="text-sm text-gray-600 mt-1">Réf: {{ $paiement->reference }}</div>
                                @endif
                                @if($paiement->notes)
                                <div class="text-sm text-gray-500 mt-1">{{ $paiement->notes }}</div>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">
                                    {{ number_format($paiement->montant, 0, ',', ' ') }} F
                                </div>
                                @if($paiement->statut == 'complet')
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Complet</span>
                                @elseif($paiement->statut == 'partiel')
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Partiel</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-400">
                    <div class="text-3xl mb-2">💸</div>
                    <p>Aucun paiement enregistré pour cette facture</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite: Résumé et actions -->
        <div class="space-y-6">
            <!-- Résumé -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Résumé</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Montant total:</span>
                        <span class="font-bold text-gray-900">{{ number_format($facture->montant, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Payé:</span>
                        <span class="font-bold text-green-600">{{ number_format($facture->montant_paye, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Reste à payer:</span>
                        <span class="font-bold text-red-600">{{ number_format($facture->montant_restant, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Statut:</span>
                            @if($facture->statut == 'payee')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                ✅ Payée
                            </span>
                            @elseif($facture->statut == 'partiel')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                ⚠️ Partielle
                            </span>
                            @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                ❌ Impayée
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Progression paiement -->
                @if($facture->statut == 'partiel')
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progression du paiement</span>
                        <span>{{ round(($facture->montant_paye / $facture->montant) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" 
                             style="width: {{ ($facture->montant_paye / $facture->montant) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Dates importantes -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📅 Dates</h3>
                <div class="space-y-3">
                    <div>
                        <div class="text-sm text-gray-500">Créée le</div>
                        <div class="font-medium">{{ $facture->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Échéance</div>
                        <div class="font-medium {{ $facture->est_en_retard ? 'text-red-600' : '' }}">
                            {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}
                            @if($facture->est_en_retard)
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded ml-2">Retard</span>
                            @endif
                        </div>
                    </div>
                    @if($facture->date_paiement_complet)
                    <div>
                        <div class="text-sm text-gray-500">Payée le</div>
                        <div class="font-medium text-green-600">
                            {{ \Carbon\Carbon::parse($facture->date_paiement_complet)->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">⚡ Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.factures.generate-pdf', $facture) }}" target="_blank"
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-medium transition">
                        📄 Télécharger PDF
                    </a>
                    <a href="{{ route('admin.factures.edit', $facture) }}"
                       class="block w-full text-center bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg font-medium transition">
                        ✏️ Modifier la facture
                    </a>
                    @if($facture->statut != 'payee')
                    <a href="{{ route('admin.paiements.create', ['facture_id' => $facture->id]) }}"
                       class="block w-full text-center bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg font-medium transition">
                        💳 Enregistrer paiement
                    </a>
                    @endif
                    <form action="{{ route('admin.factures.destroy', $facture) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="block w-full text-center bg-red-50 hover:bg-red-100 text-red-700 px-4 py-3 rounded-lg font-medium transition mt-2">
                            🗑️ Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
