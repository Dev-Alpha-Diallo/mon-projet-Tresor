@extends('layouts.app')
@section('title', $etudiant->nom)
@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $etudiant->nom }}</h1>
            <p class="text-sm text-gray-500">{{ $etudiant->filiere }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.etudiants.edit', $etudiant) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                ✏️ Modifier
            </a>
            <a href="{{ route('admin.etudiants.index') }}"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                ← Retour
            </a>
        </div>
    </div>

    {{-- Infos + Solde --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Infos personnelles --}}
        <div class="glass-effect rounded-2xl p-6 border border-gray-200/50">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📍 Informations</h2>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Maison',        'value' => $etudiant->maison->nom],
                    ['label' => 'Chambre',        'value' => $etudiant->chambre],
                    ['label' => 'Loyer mensuel',  'value' => number_format($etudiant->loyer_mensuel, 0, ',', ' ') . ' FCFA'],
                ] as $info)
                <div class="flex items-center gap-4 py-2 border-b border-gray-100 last:border-0">
                    <span class="w-36 text-sm text-gray-500">{{ $info['label'] }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $info['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Solde --}}
        <div class="glass-effect rounded-2xl p-6 border border-gray-200/50">
            <h2 class="text-lg font-bold text-gray-800 mb-4">💰 Situation financière</h2>

            @php $solde = $etudiant->solde; @endphp

            <div class="text-center py-4">
                <div class="text-4xl font-bold {{ $solde >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($solde, 0, ',', ' ') }} FCFA
                </div>
                <div class="mt-3">
                    @if($solde < 0)
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">⚠️ Débiteur</span>
                    @elseif($solde > 0)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">✅ Créditeur</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">✓ À jour</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="bg-green-50 p-3 rounded-xl text-center">
                    <p class="text-xs text-gray-500 mb-1">Total payé</p>
                    <p class="font-bold text-green-600">{{ number_format($etudiant->total_paye, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl text-center">
                    <p class="text-xs text-gray-500 mb-1">Total dû</p>
                    <p class="font-bold text-red-600">{{ number_format($etudiant->total_du, 0, ',', ' ') }} F</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Historique paiements --}}
    <div class="glass-effect rounded-2xl border border-gray-200/50 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">
                📋 Historique des paiements
                <span class="ml-2 text-sm font-normal text-gray-500">({{ $etudiant->paiements->count() }})</span>
            </h2>
            <a href="{{ route('admin.paiements.create') }}"
                class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                + Ajouter
            </a>
        </div>

        @if($etudiant->paiements->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Moyen</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($etudiant->paiements->sortByDesc('date_paiement') as $paiement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-700">
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-3 font-semibold text-green-600">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium">
                                {{ ucfirst(str_replace('_', ' ', $paiement->moyen_paiement)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">
                            {{ $paiement->remarque ?: '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-10 text-center text-gray-400">
            <svg class="mx-auto h-10 w-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="text-sm">Aucun paiement enregistré</p>
        </div>
        @endif
    </div>

</div>
@endsection