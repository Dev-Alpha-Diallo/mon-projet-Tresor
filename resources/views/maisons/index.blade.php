@extends('layouts.app')

@section('title', 'Gestion des Maisons')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Liste des Maisons</h1>
        <a href="{{ route('maisons.create') }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
            + Ajouter une Maison
        </a>
    </div>

    @if($maisons->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            Aucune maison trouvée.
        </div>
    @else
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bailleur</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($maisons as $maison)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $maison->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $maison->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $maison->adresse }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $maison->bailleur->nom ?? 'Aucun' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('maisons.edit', $maison) }}" class="text-blue-600 hover:text-blue-900">Éditer</a>
                                <form action="{{ route('maisons.destroy', $maison) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Voulez-vous vraiment supprimer cette maison ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
