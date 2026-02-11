@extends('layouts.app')

@section('title', 'Factures')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec bouton création -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📄 Listes Des Factures</h1>
            <p class="text-gray-600 mt-1">Gestion des factures pour les loyers et services</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('factures.create') }}" 
               class="bg-gradient-to-r from-primary to-primary-dark text-white px-4 py-2.5 rounded-lg font-medium hover:shadow-md transition flex items-center space-x-2">
                <span>➕</span>
                <span>Nouvelle Facture</span>
            </a>
            
            <!-- Filtres rapides -->
            <div class="relative">
                <select onchange="window.location.href = this.value" 
                        class="bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none cursor-pointer">
                    <option value="{{ route('factures.index') }}">Toutes les factures</option>
                    <option value="{{ route('factures.index', ['filter' => 'impayees']) }}" {{ request('filter') == 'impayees' ? 'selected' : '' }}>
                        Impayées
                    </option>
                    <option value="{{ route('factures.index', ['filter' => 'payees']) }}" {{ request('filter') == 'payees' ? 'selected' : '' }}>
                        Payées
                    </option>
                    <option value="{{ route('factures.index', ['filter' => 'mois_en_cours']) }}" {{ request('filter') == 'mois_en_cours' ? 'selected' : '' }}>
                        Ce mois-ci
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total factures</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalFactures }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 text-xl">📋</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Impayées</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $facturesImpayees }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <span class="text-red-600 text-xl">⚠️</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Montant dû</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($montantDu, 0, ',', ' ') }} F</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <span class="text-orange-600 text-xl">💰</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Payées ce mois</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $facturesPayeesMois }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 text-xl">✅</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des factures -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maison</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date échéance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($factures as $facture)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-mono font-bold text-primary">FACT-{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-gray-600">🏠</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $facture->maison->nom ?? 'Maison inconnue' }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($facture->type) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ \Carbon\Carbon::parse($facture->mois)->format('M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ number_format($facture->montant, 0, ',', ' ') }} F</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($facture->statut == 'payee')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Payée
                            </span>
                            @elseif($facture->statut == 'partiel')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                Partielle
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Impayée
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</div>
                            @if($facture->est_en_retard)
                            <div class="text-xs text-red-600 font-medium">En retard</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('factures.show', $facture) }}" class="text-primary hover:text-primary-dark">👁️ Voir</a>
                                <a href="{{ route('factures.edit', $facture) }}" class="text-blue-600 hover:text-blue-800">✏️ Modifier</a>
                                <a href="{{ route('factures.generate-pdf', $facture) }}" target="_blank" class="text-gray-600 hover:text-gray-800">📄 PDF</a>
                                @if($facture->statut != 'payee')
                                <a href="{{ route('paiements.create', ['facture_id' => $facture->id]) }}" class="text-green-600 hover:text-green-800 font-medium">💳 Payer</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <div class="text-4xl mb-2">📄</div>
                                <p class="text-lg font-medium text-gray-500">Aucune facture trouvée</p>
                                <p class="text-gray-400 mt-1">Créez votre première facture</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($factures->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $factures->links() }}
        </div>
        @endif
    </div>

    <!-- Notes rapides -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0 mr-3">
                <span class="text-blue-600 text-xl">💡</span>
            </div>
            <div>
                <h3 class="font-medium text-blue-800">Astuce</h3>
                <p class="text-blue-700 text-sm mt-1">
                    Utilisez le filtre "Impayées" pour voir rapidement les factures en attente de paiement. 
                    Les factures en retard plus de 7 jours sont automatiquement marquées.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
