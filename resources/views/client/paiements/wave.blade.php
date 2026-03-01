@extends('layouts.client')
@section('title', 'Payer via Wave')

@section('content')

    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-800">Payer via Wave</h1>
        <p class="text-gray-400 text-xs mt-0.5">Payez votre loyer en quelques secondes</p>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Montant + Bouton Wave --}}
    <div class="relative bg-gradient-to-br from-sky-400 to-cyan-500 rounded-3xl p-5 mb-4 overflow-hidden shadow-lg">
        <div class="absolute -top-6 -right-6 w-28 h-28 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-8 right-4 w-20 h-20 bg-white/10 rounded-full"></div>

        <p class="text-sky-100 text-xs mb-1">Montant à payer</p>
        <p class="text-white text-4xl font-bold">{{ number_format($montant, 0, ',', ' ') }}</p>
        <p class="text-sky-100 text-sm mb-5">FCFA / mois</p>

        <a href="{{ $waveLink }}" target="_blank"
            class="inline-flex items-center gap-2 bg-white text-sky-600 font-bold px-5 py-3 rounded-2xl shadow-md hover:shadow-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Payer avec Wave
        </a>
    </div>

    {{-- Instructions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h2 class="font-bold text-gray-800 text-sm mb-4">Comment ça marche ?</h2>
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-sky-600 font-bold text-xs">1</span>
                </div>
                <p class="text-sm text-gray-600">Cliquez sur <strong>"Payer avec Wave"</strong></p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-sky-600 font-bold text-xs">2</span>
                </div>
                <p class="text-sm text-gray-600">Effectuez le paiement sur Wave</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-sky-600 font-bold text-xs">3</span>
                </div>
                <p class="text-sm text-gray-600">Copiez le <strong>numéro de transaction</strong> reçu par SMS</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-sky-600 font-bold text-xs">4</span>
                </div>
                <p class="text-sm text-gray-600">Collez-le ci-dessous et soumettez</p>
            </div>
        </div>
    </div>

    {{-- Formulaire soumission transaction --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h2 class="font-bold text-gray-800 text-sm mb-4">Soumettre votre transaction</h2>

        <form method="POST" action="{{ route('client.paiement.soumettre') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                    Numéro de transaction Wave
                </label>
                <input type="text" name="transaction_id"
                    value="{{ old('transaction_id') }}"
                    placeholder="Ex: TX-XXXXXXXXXX"
                    class="w-full px-4 py-3 border rounded-xl text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-sky-400 transition uppercase
                           {{ $errors->has('transaction_id') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                @error('transaction_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">
                    💡 Retrouvez ce numéro dans le SMS de confirmation Wave
                </p>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-sky-400 to-cyan-500 hover:from-sky-500 hover:to-cyan-600
                       text-white font-semibold py-3 rounded-xl transition shadow-md text-sm">
                Soumettre la transaction
            </button>
        </form>
    </div>

    {{-- Historique demandes --}}
    @if($demandes->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50">
                <h2 class="font-bold text-gray-800 text-sm">Mes demandes</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($demandes as $demande)
    <div class="px-5 py-4 {{ $demande->statut === 'rejete' ? 'bg-red-50' : '' }}">
        <div class="flex items-center justify-between mb-2">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $demande->transaction_id }}</p>
                <p class="text-xs text-gray-400">{{ $demande->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                @if($demande->statut === 'soumis')   bg-orange-50 text-orange-500 border border-orange-200
                @elseif($demande->statut === 'valide') bg-emerald-50 text-emerald-600 border border-emerald-200
                @elseif($demande->statut === 'rejete') bg-red-50 text-red-600 border border-red-200
                @else bg-gray-50 text-gray-400 @endif">
                @if($demande->statut === 'soumis')    ⏳ En attente de validation
                @elseif($demande->statut === 'valide') ✅ Paiement validé
                @elseif($demande->statut === 'rejete') ❌ Rejeté
                @endif
            </span>
        </div>

        {{-- Motif rejet visible --}}
        @if($demande->statut === 'rejete' && $demande->note)
            <div class="mt-2 bg-red-100 border border-red-200 rounded-xl p-3 flex items-start gap-2">
                <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <div>
                    <p class="text-xs font-semibold text-red-700">Motif du rejet :</p>
                    <p class="text-xs text-red-600 mt-0.5">{{ $demande->note }}</p>
                    <p class="text-xs text-red-500 mt-1 font-medium">
                         Veuillez contacter l'administration ou soumettre une nouvelle transaction.
                    </p>
                </div>
            </div>
        @endif

        {{-- Montant --}}
        <p class="text-xs text-gray-400 mt-1">
            {{ number_format($demande->montant, 0, ',', ' ') }} FCFA
        </p>
    </div>
@endforeach
            </div>
        </div>
    @endif

@endsection