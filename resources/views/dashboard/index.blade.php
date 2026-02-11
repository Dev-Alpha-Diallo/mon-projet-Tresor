@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="p-4 space-y-4">
    <!-- Indicateurs principaux -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-3 text-white">
            <p class="text-xs">Caisse</p>
            <p class="text-lg font-bold">{{ number_format($soldeCaisse, 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 text-white">
            <p class="text-xs">Recettes</p>
            <p class="text-lg font-bold">{{ number_format($totalRecettes, 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-3 text-white">
            <p class="text-xs">Dépenses</p>
            <p class="text-lg font-bold">{{ number_format($totalDepenses, 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-3 text-white">
            <p class="text-xs">Étudiants</p>
            <p class="text-lg font-bold">{{ $nombreEtudiants }}</p>
        </div>
    </div>

    <!-- Statuts étudiants -->
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-lg border-l-4 border-red-500 p-3">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-500">Débiteurs</p>
                    <p class="font-bold text-gray-800">{{ $nombreDebiteurs }}</p>
                </div>
                <div class="text-red-500">📉</div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ number_format($totalDettes, 0, ',', ' ') }} F</p>
        </div>

        <div class="bg-white rounded-lg border-l-4 border-green-500 p-3">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-500">Créditeurs</p>
                    <p class="font-bold text-gray-800">{{ $nombreCrediteurs }}</p>
                </div>
                <div class="text-green-500">📈</div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ number_format($totalAvances, 0, ',', ' ') }} F</p>
        </div>

        <div class="bg-white rounded-lg border-l-4 border-blue-500 p-3">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-500">À jour</p>
                    <p class="font-bold text-gray-800">{{ $nombreEtudiants - $nombreDebiteurs - $nombreCrediteurs }}</p>
                </div>
                <div class="text-blue-500">✅</div>
            </div>
        </div>
    </div>

    <!-- Tableau maisons -->
    <div class="bg-white rounded-lg border">
        <div class="px-3 py-2 bg-gray-50 border-b">
            <h2 class="font-medium text-gray-800 text-sm">Situation par maison</h2>
        </div>
        <table class="w-full text-xs">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-gray-600">Maison</th>
                    <th class="px-3 py-2 text-left text-gray-600">Bailleur</th>
                    <th class="px-3 py-2 text-left text-gray-600">Solde</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($maisons as $maison)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2">
                        <div class="font-medium text-blue-600">{{ $maison->nom }}</div>
                        <div class="text-gray-400">{{ $maison->etudiants->count() }} étudiants</div>
                    </td>
                    <td class="px-3 py-2 text-gray-700">{{ $maison->bailleur->nom }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded-full text-xs {{ $maison->solde >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ number_format($maison->solde, 0, ',', ' ') }} F
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-3 py-4 text-center text-gray-400 text-xs">Aucune maison</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        <a href="{{ route('paiements.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-2 text-xs text-center transition">
            💳 Paiement
        </a>

        <a href="{{ route('factures.create') }}" 
           class="bg-orange-500 hover:bg-orange-600 text-white rounded-lg p-2 text-xs text-center transition">
            🧾 Facture
        </a>

        <a href="{{ route('factures.index') }}" 
           class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-2 text-xs text-center transition">
            📄 Factures
        </a>

        <a href="{{ route('rapports.index') }}" 
           class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-2 text-xs text-center transition">
            📊 Rapports
        </a>
    </div>
</div>
@endsection