@extends('layouts.app')

@section('title', 'Étudiants')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Étudiants</h1>
            <p class="text-sm text-gray-600">Gérer les étudiants</p>
        </div>
        <a href="{{ route('etudiants.create') }}" 
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border p-3">
        <form action="{{ route('etudiants.index') }}" method="GET" class="flex gap-2">
            <input type="text" 
                   name="nom" 
                   value="{{ request('nom') }}" 
                   placeholder="Nom..."
                   class="flex-1 px-3 py-2 border rounded-lg">
            <select name="maison_id" class="px-3 py-2 border rounded-lg">
                <option value="">Toutes les maisons</option>
                @foreach($maisons as $maison)
                    <option value="{{ $maison->id }}" {{ request('maison_id') == $maison->id ? 'selected' : '' }}>
                        {{ $maison->nom }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                🔍
            </button>
            @if(request('nom') || request('maison_id'))
                <a href="{{ route('etudiants.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg">
                    ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 500px;">
            <table class="min-w-full">
                <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900">Nom</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900">Filière</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900">Maison</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900">Chambre</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900">Loyer</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900">Solde</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-900 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants as $etudiant)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('etudiants.show', $etudiant) }}" class="text-blue-600 font-medium">
                                {{ $etudiant->nom }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $etudiant->filiere }}</td>
                        <td class="px-4 py-3 text-sm">{{ $etudiant->maison->nom }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs bg-gray-100 rounded">
                                {{ $etudiant->chambre }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }} F</td>
                        <td class="px-4 py-3">
                            @if($etudiant->solde < 0)
                                <span class="px-2 py-1 text-sm bg-red-100 text-red-800 rounded">
                                    {{ number_format($etudiant->solde, 0, ',', ' ') }} F
                                </span>
                            @elseif($etudiant->solde > 0)
                                <span class="px-2 py-1 text-sm bg-green-100 text-green-800 rounded">
                                    +{{ number_format($etudiant->solde, 0, ',', ' ') }} F
                                </span>
                            @else
                                <span class="px-2 py-1 text-sm bg-gray-100 rounded">
                                    {{ number_format($etudiant->solde, 0, ',', ' ') }} F
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex gap-3 justify-end">
                                <a href="{{ route('etudiants.edit', $etudiant) }}" class="text-blue-600">
                                    Modifier
                                </a>
                                <form action="{{ route('etudiants.destroy', $etudiant) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Supprimer cet étudiant ?')" 
                                            class="text-red-600">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">Aucun étudiant trouvé</p>
                            <a href="{{ route('etudiants.create') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                                Ajouter un étudiant
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($etudiants->count() > 0)
        <div class="bg-gray-50 px-4 py-2 border-t text-sm text-gray-600">
            {{ $etudiants->count() }} étudiant{{ $etudiants->count() > 1 ? 's' : '' }}
        </div>
        @endif
    </div>
</div>
@endsection