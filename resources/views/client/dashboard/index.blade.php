@extends('layouts.client')
@section('title', 'Tableau de bord')

@section('content')

    {{-- Notification nouveau paiement --}}
@if(isset($notif) && $notif)
    <div id="notif-banner"
        class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-emerald-800">Nouveau paiement enregistré !</p>
                <p class="text-xs text-emerald-600">
                    {{ number_format($notif['montant'], 0, ',', ' ') }} FCFA le {{ $notif['date'] }}
                </p>
            </div>
        </div>
        <button onclick="document.getElementById('notif-banner').remove()"
            class="text-emerald-400 hover:text-emerald-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Bonjour, {{ explode(' ', $etudiant->nom)[0] }} </h1>
        <p class="text-gray-400 text-xs mt-0.5">
            {{ $etudiant->filiere }} · Chambre {{ $etudiant->chambre }} · {{ $etudiant->maison->nom ?? 'N/A' }}
        </p>
    </div>

    {{-- Solde principal --}}
    <div class="relative rounded-3xl p-5 mb-4 overflow-hidden shadow-lg
        {{ $solde >= 0 ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : 'bg-gradient-to-br from-rose-400 to-red-500' }}">
        {{-- Cercles décoratifs --}}
        <div class="absolute -top-6 -right-6 w-28 h-28 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-8 -right-2 w-20 h-20 bg-white/10 rounded-full"></div>

        <p class="text-white/80 text-xs font-medium uppercase tracking-wide mb-1">
            {{ $solde >= 0 ? ' Solde créditeur' : ' Solde débiteur' }}
        </p>
        <p class="text-white text-4xl font-bold">
            {{ $solde >= 0 ? '+' : '' }}{{ number_format($solde, 0, ',', ' ') }}
        </p>
        <p class="text-white/70 text-sm">FCFA</p>
    </div>

    {{-- Cards Payé / Dû --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-xs text-gray-400 mb-0.5">Total payé</p>
            <p class="text-lg font-bold text-gray-800">{{ number_format($totalPaye, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-400">FCFA</p>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-xs text-gray-400 mb-0.5">Total dû</p>
            <p class="text-lg font-bold text-gray-800">{{ number_format($totalDu, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-400">FCFA</p>
        </div>
    </div>

    {{-- Loyer mensuel --}}
    <div class="bg-gradient-to-r from-sky-400 to-cyan-500 rounded-2xl p-4 mb-6 shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sky-100 text-xs">Loyer mensuel</p>
                    <p class="text-white font-bold text-lg">{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sky-100 text-xs">Depuis le</p>
                <p class="text-white text-sm font-semibold">
                    {{ \Carbon\Carbon::parse($etudiant->date_debut)->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
                               

                      {{-- Suivi mensuel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-gray-800 text-sm">Suivi mensuel</h2>
            <p class="text-gray-400 text-xs mt-0.5">
                {{ collect($mois)->where('paye', false)->count() }} mois non payé(s)
            </p>
        </div>
        <div class="w-8 h-8 bg-orange-50 rounded-xl flex items-center justify-center">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

    <div class="divide-y divide-gray-50">
        @foreach($mois as $m)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0
                        {{ $m['paye'] ? 'bg-emerald-50' : 'bg-red-50' }}">
                        @if($m['paye'])
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $m['label'] }}</p>
                        <p class="text-xs {{ $m['paye'] ? 'text-emerald-500' : 'text-red-400' }}">
                            {{ $m['paye'] ? 'Payé' : 'Non payé' }}
                        </p>
                    </div>
                </div>
                <p class="text-sm font-semibold {{ $m['paye'] ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ number_format($m['montant'], 0, ',', ' ') }} FCFA
                </p>
            </div>
        @endforeach
    </div>
</div>
    {{-- Historique --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-800 text-sm">Historique des paiements</h2>
                <p class="text-gray-400 text-xs mt-0.5">{{ $paiements->count() }} paiement(s)</p>
            </div>
            <div class="w-8 h-8 bg-sky-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        @if($paiements->isEmpty())
            <div class="px-5 py-10 text-center">
                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="font-medium text-gray-500 text-sm">Aucun paiement enregistré</p>
                <p class="text-xs text-gray-400 mt-1">Contactez l'administration.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($paiements as $paiement)
                    <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('client.paiements.show', $paiement->id) }}"
                                class="w-9 h-9 bg-sky-50 hover:bg-sky-100 text-sky-500 rounded-xl flex items-center justify-center transition shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ ucfirst($paiement->moyen_paiement ?? 'Non précisé') }}
                                    @if($paiement->remarque) · {{ Str::limit($paiement->remarque, 20) }} @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-600">
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection