@extends('layouts.app')

@section('title', 'Étudiants')

@section('content')
<div class="space-y-4">
   <!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Étudiants</h1>
        <p class="text-sm text-gray-600">Gérer les étudiants</p>
    </div>

    <!-- Groupe des boutons -->
    <div class="flex flex-wrap items-center gap-3">

        <!-- Bouton Export -->
        <div class="relative group">
            <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter
            </button>

            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <a href="{{ route('admin.etudiants.export.tous', ['tri' => 'maison']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Triée par maison
                    </a>
                    <a href="{{ route('admin.etudiants.export.tous', ['tri' => 'nom']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        Triée par nom (A-Z)
                    </a>
                    <div class="px-3 py-2 bg-gray-50 border-t border-b mt-1">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Débiteurs</p>
                    </div>
                <a href="{{ route('admin.etudiants.export.debiteurs') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-b-lg">
                    Liste débiteurs uniquement
                </a>
            </div>
        </div>

        <!-- Bouton Ajouter -->
        <a href="{{ route('admin.etudiants.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors w-full sm:w-auto justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter
        </a>

    </div>

</div>


   <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
    <form action="{{ route('admin.etudiants.index') }}" 
          method="GET" 
          class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

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
               class="w-full sm:w-32 px-4 py-2 border border-gray-300 rounded-xl 
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                      transition-all text-sm">

        {{-- Filtre maison --}}
        <select name="maison_id" 
                onchange="this.form.submit()"
                class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-xl 
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

        <div class="flex gap-2">
            {{-- Bouton --}}
            <button type="submit" 
                    class="flex-1 sm:flex-none px-4 py-2 bg-indigo-600 text-white rounded-xl 
                        hover:bg-indigo-700 transition-all text-sm font-medium">
                🔍
            </button>

            {{-- Reset --}}
            @if(request('nom') || request('maison_id') || request('chambre'))
                <a href="{{ route('admin.etudiants.index') }}" 
                class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 rounded-xl text-center
                        hover:bg-gray-200 transition-all text-sm font-medium">
                    ✕
                </a>
            @endif
        </div>

    </form>
</div>


    <!-- Table -->
    <div class="bg-white rounded-lg border overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 500px;">
            <table class="min-w-full">
                <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-left">
                        <th class="px-3 sm:px-4 py-3 text-xs font-semibold text-gray-900">Nom</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-xs font-semibold text-gray-900">Filière</th>
                        <th class="px-3 sm:px-4 py-3 text-xs font-semibold text-gray-900">Maison</th>
                        <th class="hidden md:table-cell px-4 py-3 text-xs font-semibold text-gray-900">Chambre</th>
                        <th class="hidden lg:table-cell px-4 py-3 text-xs font-semibold text-gray-900">Loyer</th>
                        <th class="px-3 sm:px-4 py-3 text-xs font-semibold text-gray-900">Solde</th>
                        <th class="px-3 sm:px-4 py-3 text-xs font-semibold text-gray-900 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants as $etudiant)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-3 sm:px-4 py-3">
                            <a href="{{ route('admin.etudiants.show', $etudiant) }}" class="text-blue-600 font-medium text-sm">
                                {{ $etudiant->nom }}
                            </a>
                        </td>
                        <td class="hidden sm:table-cell px-4 py-3 text-sm">{{ $etudiant->filiere }}</td>
                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm">{{ $etudiant->maison->nom }}</td>
                        <td class="hidden md:table-cell px-4 py-3">
                            <span class="px-2 py-1 text-xs bg-gray-100 rounded">
                                {{ $etudiant->chambre }}
                            </span>
                        </td>
                        <td class="hidden lg:table-cell px-4 py-3 text-sm">{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }} F</td>
                        <td class="px-3 sm:px-4 py-3">
                            @if($etudiant->solde < 0)
                                <span class="px-2 py-1 text-xs sm:text-sm bg-red-100 text-red-800 rounded whitespace-nowrap">
                                    {{ number_format($etudiant->solde, 0, ',', ' ') }} F
                                </span>
                            @elseif($etudiant->solde > 0)
                                <span class="px-2 py-1 text-xs sm:text-sm bg-green-100 text-green-800 rounded whitespace-nowrap">
                                    +{{ number_format($etudiant->solde, 0, ',', ' ') }} F
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded whitespace-nowrap">
                                    {{ number_format($etudiant->solde, 0, ',', ' ') }} F
                                </span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 py-3 text-right">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                               <a href="{{ route('admin.etudiants.edit', $etudiant) }}"
                                    class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 font-medium text-center">
                                    Modifier
                                    </a>

                                <form action="{{ route('admin.etudiants.destroy', $etudiant) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Supprimer cet étudiant ?')"
                                        class="flex items-center justify-center space-x-1 text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-300 hover:text-white transition-all duration-200 font-medium w-full">

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
                            <a href="{{ route('admin.etudiants.create') }}" class="inline-flex items-center justify-center space-x-2 px-4 py-2 mt-4 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
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
