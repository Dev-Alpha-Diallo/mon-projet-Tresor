@extends('layouts.app')

@section('title', 'Bailleurs')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-black-800">🏢 Bailleurs</h1>
                <p class="text-black-800 mt-1">Gestion des propriétaires</p>
            </div>
            <a href="{{ route('bailleurs.create') }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                + Ajouter
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left p-4 text-gray-600 font-medium">Nom</th>
                    <th class="text-left p-4 text-gray-600 font-medium">Téléphone</th>
                    <th class="text-left p-4 text-gray-600 font-medium">Maisons</th>
                    <th class="text-left p-4 text-gray-600 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bailleurs as $bailleur)
                <tr class="border-b hover:bg-gray-50">
                    <!-- Nom -->
                    <td class="p-4">
                        <div class="font-medium">{{ $bailleur->nom }}</div>
                    </td>
                    
                    <!-- Téléphone -->
                    <td class="p-4">
                        <div class="text-gray-600">{{ $bailleur->telephone ?: '-' }}</div>
                    </td>
                    
                    <!-- Maisons -->
                    <td class="p-4">
                        <div class="text-gray-700">{{ $bailleur->maisons->count() }} maison(s)</div>
                        @if($bailleur->maisons->count() > 0)
                            <div class="text-xs text-gray-400 mt-1">
                                @foreach($bailleur->maisons->take(2) as $maison)
                                    {{ $maison->nom }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                                @if($bailleur->maisons->count() > 2)
                                    +{{ $bailleur->maisons->count() - 2 }} autres
                                @endif
                            </div>
                        @endif
                    </td>
                    
                    <!-- Actions -->
                    <td class="p-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('bailleurs.edit', $bailleur) }}" 
                               class="text-gray-400 hover:text-primary" title="Modifier">
                                ✏️
                            </a>
                            <form action="{{ route('bailleurs.destroy', $bailleur) }}" method="POST" 
                                  onsubmit="return confirm('Supprimer ce bailleur ?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500" title="Supprimer">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">
                        Aucun bailleur enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Compteur simple -->
        @if($bailleurs->count() > 0)
        <div class="p-4 border-t text-sm text-gray-500">
            {{ $bailleurs->count() }} bailleur(s)
        </div>
        @endif
    </div>
</div>
@endsection