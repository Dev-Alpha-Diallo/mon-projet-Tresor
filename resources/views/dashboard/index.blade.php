@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="mt-1 text-sm text-gray-500">Vue d'ensemble de votre trésorerie</p>
        </div>
       <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl shadow-md">
    
            <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>

            <span class="text-sm font-semibold tracking-wide">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </span>

        </div>

    </div>

    <!-- Indicateurs principaux -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-effect rounded-2xl p-6 border border-gray-200/50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Solde de la caisse</p>
                    <p class="mt-2 text-3xl font-bold {{ $soldeCaisse >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($soldeCaisse, 0, ',', ' ') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">FCFA</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-gray-200/50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total recettes</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ number_format($totalRecettes, 0, ',', ' ') }}</p>
                    <p class="mt-1 text-xs text-gray-500">FCFA</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-gray-200/50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total dépenses</p>
                    <p class="mt-2 text-3xl font-bold text-orange-600">{{ number_format($totalDepenses, 0, ',', ' ') }}</p>
                    <p class="mt-1 text-xs text-gray-500">FCFA</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-gray-200/50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Étudiants</p>
                    <p class="mt-2 text-3xl font-bold text-purple-600">{{ $nombreEtudiants }}</p>
                    <p class="mt-1 text-xs text-gray-500">Inscrits</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statuts étudiants -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-effect rounded-2xl p-6 border-l-4 border-red-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Étudiants débiteurs</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">{{ $nombreDebiteurs }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <span class="text-xs text-gray-500">Total dettes</span>
                <span class="text-sm font-semibold text-red-600">{{ number_format($totalDettes, 0, ',', ' ') }} F</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Étudiants créditeurs</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">{{ $nombreCrediteurs }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <span class="text-xs text-gray-500">Total avances</span>
                <span class="text-sm font-semibold text-green-600">{{ number_format($totalAvances, 0, ',', ' ') }} F</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">À jour</p>
                    <p class="mt-2 text-2xl font-bold text-blue-600">{{ $nombreEtudiants - $nombreDebiteurs - $nombreCrediteurs }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <span class="text-xs text-gray-500">Pourcentage</span>
                <span class="text-sm font-semibold text-blue-600">{{ $nombreEtudiants > 0 ? round(($nombreEtudiants - $nombreDebiteurs - $nombreCrediteurs) / $nombreEtudiants * 100) : 0 }}%</span>
            </div>
        </div>
    </div>

    <!-- Maisons -->
    <div class="glass-effect rounded-2xl border border-gray-200/50 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b">
            <h2 class="text-lg font-bold text-gray-900">Maisons ({{ $maisons->count() }})</h2>
        </div>
        <div class="overflow-x-auto" style="max-height: 400px;">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Maison</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Bailleur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Étudiants</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Solde</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($maisons as $maison)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $maison->nom }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($maison->adresse, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $maison->bailleur->nom }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $maison->etudiants->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $maison->solde >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ number_format($maison->solde, 0, ',', ' ') }} F
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucune maison</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="glass-effect rounded-2xl p-6 border border-gray-200/50">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Actions rapides</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('paiements.create') }}" class="group rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <p class="font-semibold text-sm">Paiement</p>
            </a>

            <a href="{{ route('factures.create') }}" class="group rounded-xl bg-gradient-to-br from-orange-500 to-red-600 p-6 text-white hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-semibold text-sm"> Creer Facture</p>
            </a>

            <a href="{{ route('factures.index') }}" class="group rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 text-white hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-semibold text-sm">Factures</p>
            </a>

            <a href="{{ route('rapports.index') }}" class="group rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 p-6 text-white hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="font-semibold text-sm">Rapports</p>
            </a>

            <a href="{{ route('etudiants.export.tous') }}" class="group rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 p-6 text-white hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="font-semibold text-sm">Liste tous</p>
            </a>

            <a href="{{ route('etudiants.export.debiteurs') }}" class="group rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 p-6 text-white hover:shadow-xl transition-all hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="font-semibold text-sm">Débiteurs</p>
            </a>
        </div>
    </div>
</div>
@endsection