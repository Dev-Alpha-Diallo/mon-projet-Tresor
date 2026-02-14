@extends('layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="space-y-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Paiements étudiants</h1>
            <p class="text-sm text-gray-600">Gestion des paiements</p>
        </div>

        <a href="{{ route('admin.paiements.create') }}" 
           class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Enregistrer</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border p-3">
        <form action="{{ route('admin.paiements.index') }}" method="GET" 
              class="flex flex-col sm:flex-row gap-2">

            <input type="text" 
                   name="nom" 
                   value="{{ request('nom') }}" 
                   placeholder="Rechercher par étudiant"
                   class="flex-1 px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all">

            <select name="maison_id"
                    class="px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <option value="">Toutes les maisons</option>
                @foreach($maisons as $maison)
                    <option value="{{ $maison->id }}" {{ request('maison_id') == $maison->id ? 'selected' : '' }}>
                        {{ $maison->nom }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                  class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200 text-sm font-medium">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Étudiant</th>
                        <th class="px-6 py-3">Maison</th>
                        <th class="px-6 py-3">Montant</th>
                        <th class="px-6 py-3">Moyen</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <!-- Date -->
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </td>

                        <!-- Étudiant -->
                       <td class="px-6 py-4">
                            @if(optional($paiement->etudiant)->id)
                                <a href="{{ route('admin.etudiants.show', optional($paiement->etudiant)->id) }}"
                                class="text-blue-600 font-medium hover:underline text-sm">
                                    {{ optional($paiement->etudiant)->nom }}
                                </a>
                            @else
                                <span class="text-gray-400 italic text-sm">
                                    Étudiant supprimé
                                </span>
                            @endif

                        </td>


                        <!-- Maison -->
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ optional(optional($paiement->etudiant)->maison)->nom ?? '—' }}

                        </td>

                        <!-- Montant -->
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </td>

                        <!-- Moyen -->
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs bg-gray-100 rounded">
                                {{ ucfirst(str_replace('_', ' ', $paiement->moyen_paiement)) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">

                                <!-- Modifier -->
                                <a href="{{ route('admin.paiements.edit', $paiement) }}" 
                                   class="flex items-center gap-1 text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.414 2.586a2 2 0 010 2.828l-10 10a1 1 0 01-.293.207l-5 2a1 1 0 01-1.272-1.272l2-5a1 1 0 01.207-.293l10-10a2 2 0 012.828 0z"/>
                                    </svg>
                                    <span>Modifier</span>
                                </a>

                                <!-- Supprimer -->
                                <form action="{{ route('admin.paiements.destroy', $paiement) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr ?')">
                                    @csrf
                                    @method('DELETE')

                                    <!-- <button type="submit"
                                            class="flex items-center gap-1 text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-300 hover:text-white transition-all font-medium">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M6 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8zM4 5h12v1H4V5zm2-2h8l1 2H5l1-2z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span>Supprimer</span>
                                    </button> -->
                                </form>

                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="text-sm text-gray-500">
                                Aucun paiement enregistré
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($paiements->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t">
            {{ $paiements->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
