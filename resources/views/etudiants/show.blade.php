@extends('layouts.app')

@section('title', $etudiant->nom)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $etudiant->nom }}</h1>
                <p class="text-gray-500">{{ $etudiant->filiere }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('etudiants.edit', $etudiant) }}" 
                   class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                    Modifier
                </a>
                <a href="{{ route('etudiants.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Section principale -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Infos personnelles -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📍 Information</h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <div class="w-32 text-gray-500">Maison</div>
                    <a href="{{ route('maisons.show', $etudiant->maison) }}" 
                       class="text-primary hover:underline">{{ $etudiant->maison->nom }}</a>
                </div>
                <div class="flex items-center">
                    <div class="w-32 text-gray-500">Chambre</div>
                    <div class="font-medium">{{ $etudiant->chambre }}</div>
                </div>
                <div class="flex items-center">
                    <div class="w-32 text-gray-500">Loyer mensuel</div>
                    <div class="font-medium">{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        <!-- Situation financière -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">💰 Solde</h2>
            <div class="space-y-4">
                <!-- Solde principal -->
                <div class="text-center py-4">
                    <div class="text-3xl font-bold {{ $etudiant->solde >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($etudiant->solde, 0, ',', ' ') }} FCFA
                    </div>
                    <div class="mt-2">
                        @if($etudiant->solde < 0)
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full">Débiteur</span>
                        @elseif($etudiant->solde > 0)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">Créditeur</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full">À jour</span>
                        @endif
                    </div>
                </div>

                <!-- Détails -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="text-gray-500">Total payé</div>
                        <div class="font-medium text-green-600">{{ number_format($etudiant->total_paye, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="text-gray-500">Total dû</div>
                        <div class="font-medium text-red-600">{{ number_format($etudiant->total_du, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des paiements -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">📋 Historique des paiements</h2>
            <a href="{{ route('paiements.create') }}?etudiant={{ $etudiant->id }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary-dark">
                + Ajouter paiement
            </a>
        </div>
        
        @if($etudiant->paiements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-gray-600 font-medium">Date</th>
                        <th class="p-4 text-left text-gray-600 font-medium">Montant</th>
                        <th class="p-4 text-left text-gray-600 font-medium">Moyen</th>
                        <th class="p-4 text-left text-gray-600 font-medium">Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etudiant->paiements as $paiement)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td class="p-4 font-medium text-green-600">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs">
                                {{ $paiement->moyen_paiement }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500">{{ $paiement->remarque ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-400">
            Aucun paiement enregistré
        </div>
        @endif
    </div>
</div>
@endsection