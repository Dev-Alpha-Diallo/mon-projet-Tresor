@extends('layouts.client')
@section('title', 'Détail du paiement')

@section('content')

    {{-- Bouton retour --}}
    <div class="mb-5">
        <a href="{{ route('client.dashboard') }}"
            class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour
        </a>
    </div>

    {{-- Header montant --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-5 mb-4 text-white shadow-md">
        <p class="text-blue-200 text-sm mb-1">Montant payé</p>
        <p class="text-4xl font-bold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p>
        <div class="flex items-center gap-2 mt-3">
            <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-blue-200 text-sm">
                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                • {{ \Carbon\Carbon::parse($paiement->date_paiement)->diffForHumans() }}
            </p>
        </div>
    </div>

    {{-- Détails --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">Détails du paiement</h2>
        </div>

        <div class="divide-y divide-gray-50">

            <div class="px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-500">Montant</span>
                </div>
                <span class="font-semibold text-gray-800 text-sm">
                    {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                </span>
            </div>

            <div class="px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-500">Date de paiement</span>
                </div>
                <span class="font-semibold text-gray-800 text-sm">
                    {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                </span>
            </div>

            <div class="px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-500">Moyen de paiement</span>
                </div>
                <span class="font-semibold text-gray-800 text-sm">
                    {{ ucfirst($paiement->moyen_paiement ?? 'Non précisé') }}
                </span>
            </div>

            <div class="px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-500">Maison</span>
                </div>
                <span class="font-semibold text-gray-800 text-sm">
                    {{ $paiement->etudiant->maison->nom ?? 'N/A' }}
                </span>
            </div>

            @if($paiement->remarque)
                <div class="px-5 py-3.5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 bg-gray-50 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-500">Remarque</span>
                    </div>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-3 ml-12">
                        {{ $paiement->remarque }}
                    </p>
                </div>
            @endif

        </div>
    </div>

@endsection