@extends('layouts.app')

@section('title', 'Paiements aux bailleurs')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Paiements aux bailleurs</h1>
        <a href="{{ route('admin.paiements-bailleurs.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            ➕ Enregistrer un paiement
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bailleur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maison</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Moyen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paiementsBailleurs as $paiement)
                    <tr>
                        <td class="px-6 py-4">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="text-blue-600 hover:underline">
                                {{ $paiement->bailleur->nom }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $paiement->maison->nom }}</td>
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
                                <a href="{{ route('admin.paiements-bailleurs.edit', $paiement) }}" class="text-blue-600 hover:underline">
                                    Modifier
                                </a>
                               <form action="{{ route('admin.paiements-bailleurs.destroy', $paiement) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
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

        @if($paiementsBailleurs->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $paiementsBailleurs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
