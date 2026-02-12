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
           class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter
        </a>
    </div>

   <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
    <form action="{{ route('etudiants.index') }}" 
          method="GET" 
          class="flex flex-col sm:flex-row gap-3 items-center">

        {{-- Recherche par nom --}}
        <input type="text" 
               name="nom" 
               value="{{ request('nom') }}" 
               placeholder="Nom..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-xl 
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                      transition-all text-sm">

        {{-- Recherche par chambre --}}
        <input type="number" 
               name="chambre" 
               value="{{ request('chambre') }}" 
               placeholder="N° Chambre"
               class="w-32 px-4 py-2 border border-gray-300 rounded-xl 
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                      transition-all text-sm">

        {{-- Filtre maison --}}
        <select name="maison_id" 
                onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-300 rounded-xl 
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                       transition-all text-sm">
            <option value="">Toutes les maisons</option>
            @foreach($maisons as $maison)
                <option value="{{ $maison->id }}" 
                    {{ request('maison_id') == $maison->id ? 'selected' : '' }}>
                    {{ $maison->nom }}
                </option>
            @endforeach
        </select>

        {{-- Bouton --}}
        <button type="submit" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-xl 
                       hover:bg-indigo-700 transition-all text-sm font-medium">
            🔍
        </button>

        {{-- Reset --}}
        @if(request('nom') || request('maison_id') || request('chambre'))
            <a href="{{ route('etudiants.index') }}" 
               class="px-4 py-2 bg-gray-100 rounded-xl 
                      hover:bg-gray-200 transition-all text-sm font-medium">
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
                               <a href="{{ route('etudiants.edit', $etudiant) }}"
                                    class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 font-medium">
                                    Modifier
                                    </a>

                                <form action="{{ route('etudiants.destroy', $etudiant) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Supprimer cet étudiant ?')"
                                        class="flex items-center space-x-1 text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-300 hover:text-white-700 transition-all duration-200 font-medium">

                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8zM4 5h12v1H4V5zm2-2h8l1 2H5l1-2z"
                                                clip-rule="evenodd"/>
                                        </svg>

                                        <span>Supprimer</span>
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
                            <a href="{{ route('etudiants.create') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
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