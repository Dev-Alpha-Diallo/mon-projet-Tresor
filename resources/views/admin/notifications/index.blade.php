@extends('layouts.app')
@section('title', 'Notifications Wave')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Notifications Wave</h1>
            <p class="text-gray-500 text-sm mt-1">Demandes de paiement en attente de validation</p>
        </div>
        @if($enAttente > 0)
            <div class="flex items-center gap-2 bg-orange-100 text-orange-700 px-4 py-2 rounded-xl font-semibold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                {{ $enAttente }} en attente
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="flex gap-2 flex-wrap">
        <button onclick="filtrer('tous')" id="btn-tous"
            class="filtre-btn px-4 py-2 rounded-xl text-sm font-medium bg-indigo-600 text-white transition">
            Tous ({{ $demandes->count() }})
        </button>
        <button onclick="filtrer('soumis')" id="btn-soumis"
            class="filtre-btn px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 text-gray-600 hover:bg-orange-100 hover:text-orange-600 transition">
            ⏳ En attente ({{ $demandes->where('statut', 'soumis')->count() }})
        </button>
        <button onclick="filtrer('valide')" id="btn-valide"
            class="filtre-btn px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 text-gray-600 hover:bg-emerald-100 hover:text-emerald-600 transition">
            ✅ Validés ({{ $demandes->where('statut', 'valide')->count() }})
        </button>
        <button onclick="filtrer('rejete')" id="btn-rejete"
            class="filtre-btn px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 text-gray-600 hover:bg-red-100 hover:text-red-600 transition">
            ❌ Rejetés ({{ $demandes->where('statut', 'rejete')->count() }})
        </button>
    </div>

    {{-- Liste demandes --}}
    @if($demandes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="font-medium text-gray-500">Aucune demande de paiement</p>
        </div>
    @else
        <div class="space-y-3" id="liste-demandes">
            @foreach($demandes as $demande)
                <div class="demande-item bg-white rounded-2xl border shadow-sm overflow-hidden
                    {{ $demande->statut === 'soumis' ? 'border-orange-200' : ($demande->statut === 'valide' ? 'border-emerald-200' : 'border-red-200') }}"
                    data-statut="{{ $demande->statut }}">

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            {{-- Info étudiant --}}
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                                    {{ $demande->statut === 'soumis' ? 'bg-orange-100' : ($demande->statut === 'valide' ? 'bg-emerald-100' : 'bg-red-100') }}">
                                    <svg class="w-5 h-5 {{ $demande->statut === 'soumis' ? 'text-orange-500' : ($demande->statut === 'valide' ? 'text-emerald-500' : 'text-red-500') }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                {{-- Avant --}}
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $demande->etudiant->nom }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ $demande->etudiant->maison->nom ?? 'N/A' }} —
                                            Chambre {{ $demande->etudiant->chambre }}
                                        </p>
                                    </div>

                                    {{-- Après --}}
                                    <div>
                                        <p class="font-bold text-gray-800">
                                            {{ $demande->etudiant->nom ?? 'Étudiant supprimé' }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $demande->etudiant->maison->nom ?? 'N/A' }} —
                                            Chambre {{ $demande->etudiant->chambre ?? 'N/A' }}
                                        </p>
                                    </div>
                            </div>

                            {{-- Statut badge --}}
                            <span class="text-xs font-semibold px-3 py-1.5 rounded-full shrink-0
                                {{ $demande->statut === 'soumis' ? 'bg-orange-50 text-orange-600 border border-orange-200' :
                                   ($demande->statut === 'valide' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' :
                                   'bg-red-50 text-red-600 border border-red-200') }}">
                                {{ $demande->statut === 'soumis' ? '⏳ En attente' :
                                   ($demande->statut === 'valide' ? '✅ Validé' : '❌ Rejeté') }}
                            </span>
                        </div>

                        {{-- Détails transaction --}}
                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-xs text-gray-400 mb-0.5">Montant</p>
                                <p class="font-bold text-gray-800">{{ number_format($demande->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-xs text-gray-400 mb-0.5">Transaction</p>
                                <p class="font-bold text-indigo-600 text-sm">{{ $demande->transaction_id }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-xs text-gray-400 mb-0.5">Date</p>
                                <p class="font-bold text-gray-800 text-sm">{{ $demande->created_at->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $demande->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Note rejet --}}
                        @if($demande->note)
                            <div class="mt-3 bg-red-50 border border-red-100 rounded-xl p-3">
                                <p class="text-xs text-red-500 font-medium">Motif de rejet :</p>
                                <p class="text-sm text-red-700">{{ $demande->note }}</p>
                            </div>
                        @endif

                        {{-- Actions --}}
                        @if($demande->statut === 'soumis')
                            <div class="mt-4 flex items-center gap-3">
                                {{-- Valider --}}
                                <form method="POST" action="{{ route('admin.notifications.valider', $demande->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-2.5 rounded-xl transition text-sm shadow-sm">
                                        ✅ Valider le paiement
                                    </button>
                                </form>

                                {{-- Rejeter --}}
                                <button onclick="toggleRejet({{ $demande->id }})"
                                    class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-xl transition text-sm border border-red-200">
                                    ❌ Rejeter
                                </button>
                            </div>

                            {{-- Formulaire rejet (caché par défaut) --}}
                            <div id="rejet-{{ $demande->id }}" class="hidden mt-3">
                                <form method="POST" action="{{ route('admin.notifications.rejeter', $demande->id) }}">
                                    @csrf
                                    <input type="text" name="note" required
                                        placeholder="Motif du rejet..."
                                        class="w-full px-4 py-2.5 border border-red-200 rounded-xl text-sm bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300 mb-2">
                                    <button type="submit"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                                        Confirmer le rejet
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<script>
    function toggleRejet(id) {
        const el = document.getElementById('rejet-' + id);
        el.classList.toggle('hidden');
    }

    function filtrer(statut) {
        const items = document.querySelectorAll('.demande-item');
        items.forEach(item => {
            if (statut === 'tous' || item.dataset.statut === statut) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        // Reset boutons
        document.querySelectorAll('.filtre-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });

        // Activer le bouton cliqué
        const btnActif = document.getElementById('btn-' + statut);
        if (btnActif) {
            btnActif.classList.add('bg-indigo-600', 'text-white');
            btnActif.classList.remove('bg-gray-100', 'text-gray-600');
        }
    }
</script>

@endsection