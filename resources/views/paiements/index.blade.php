@extends('layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Paiements étudiants</h1>
        <a href="{{ route('paiements.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            ➕ Enregistrer un paiement
        </a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
    <form action="{{ route('paiements.index') }}" method="GET" class="flex flex-col md:flex-row md:space-x-2 space-y-2 md:space-y-0">
        <!-- Recherche par nom d'étudiant -->
        <input type="text" name="nom" value="{{ request('nom') }}" placeholder="Rechercher par étudiant"
            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <!-- Filtrer par maison -->
        <select name="maison_id"
            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Toutes les maisons</option>
            @foreach($maisons as $maison)
                <option value="{{ $maison->id }}" {{ request('maison_id') == $maison->id ? 'selected' : '' }}>
                    {{ $maison->nom }}
                </option>
            @endforeach
        </select>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Filtrer</button>
    </form>
</div>


    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maison</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Moyen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paiements as $paiement)
                    <tr>
                        <td class="px-6 py-4">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('etudiants.show', $paiement->etudiant) }}" class="text-blue-600 hover:underline">
                                {{ $paiement->etudiant->nom }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $paiement->etudiant->maison->nom }}</td>
                        <td class="px-6 py-4 font-semibold text-green-600">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-gray-100 rounded text-sm">
                                {{ ucfirst(str_replace('_', ' ', $paiement->moyen_paiement)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('paiements.edit', $paiement) }}" class="text-blue-600 hover:underline">
                                    Modifier
                                </a>
                                <form action="{{ route('paiements.destroy', $paiement) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun paiement enregistré
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paiements->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $paiements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection