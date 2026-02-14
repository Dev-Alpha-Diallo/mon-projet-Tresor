@extends('layouts.app')

@section('title', 'Gestion des Maisons')

@section('content')
<div class="space-y-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Liste des Maisons</h1>
        <a href="{{ route('admin.maisons.create') }}" 
           class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Ajouter une Maison</span>
        </a>
    </div>

    <!-- Empty state -->
    @if($maisons->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
            Aucune maison trouvée.
        </div>
    @else
        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Nom</th>
                        <th class="px-6 py-3">Adresse</th>
                        <th class="px-6 py-3">Bailleur</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($maisons as $maison)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $maison->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $maison->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $maison->adresse }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $maison->bailleur->nom ?? 'Aucun' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end gap-2">
                            <!-- Modifier -->
                            <a href="{{ route('admin.maisons.edit', $maison) }}" 
                               class="flex items-center gap-1 text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all font-medium">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.414 2.586a2 2 0 010 2.828l-10 10a1 1 0 01-.293.207l-5 2a1 1 0 01-1.272-1.272l2-5a1 1 0 01.207-.293l10-10a2 2 0 012.828 0z"/>
                                </svg>
                                <span>Modifier</span>
                            </a>

                            <!-- Supprimer -->
                            <form action="{{ route('admin.maisons.destroy', $maison) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <!-- <button type="submit" 
                                        onclick="return confirm('Voulez-vous vraiment supprimer cette maison ?')" 
                                        class="flex items-center gap-1 text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-300 hover:text-white transition-all font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8zM4 5h12v1H4V5zm2-2h8l1 2H5l1-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Supprimer</span>
                                </button> -->
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-3 border-t text-sm text-gray-600 flex items-center justify-between">
            <div>
                {{ $maisons->total() }} maison{{ $maisons->total() > 1 ? 's' : '' }}
            </div>
            <div>
                {{ $maisons->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
