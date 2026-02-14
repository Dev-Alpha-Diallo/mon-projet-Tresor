@extends('layouts.app')

@section('title', 'Enregistrer un paiement bailleur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Enregistrer un paiement bailleur
        </h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <form action="{{ route('admin.paiements-bailleurs.store') }}" method="POST">
            @csrf

            <div class="space-y-6">

                <!-- Bailleur -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bailleur</label>
                    <select name="bailleur_id" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Sélectionner un bailleur</option>
                        @foreach($bailleurs as $bailleur)
                            <option value="{{ $bailleur->id }}" {{ old('bailleur_id') == $bailleur->id ? 'selected' : '' }}>
                                {{ $bailleur->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('bailleur_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maison -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maison</label>
                    <select name="maison_id" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Sélectionner une maison</option>
                        @foreach($maisons as $maison)
                            <option value="{{ $maison->id }}" {{ old('maison_id') == $maison->id ? 'selected' : '' }}>
                                {{ $maison->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('maison_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="montant" required min="0" step="1"
                        value="{{ old('montant') }}"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('montant')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date de paiement</label>
                    <input type="date" name="date_paiement" required
                        value="{{ old('date_paiement', date('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('date_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mode de paiement -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mode de paiement</label>
                    <select name="moyen_paiement" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Sélectionner</option>
                        <option value="especes" {{ old('moyen_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="mobile_money" {{ old('moyen_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="virement" {{ old('moyen_paiement') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                    </select>
                    @error('moyen_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remarque -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Remarque (optionnel)</label>
                    <textarea name="remarque" rows="3"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ old('remarque') }}</textarea>
                    @error('remarque')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Boutons -->
            <div class="flex items-center space-x-4 mt-8">
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl 
                           bg-gradient-to-r from-indigo-500 to-purple-600 
                           text-white font-semibold shadow-md 
                           hover:shadow-xl hover:scale-[1.02] 
                           transition-all duration-200">
                    💾 Enregistrer
                </button>

                <a href="{{ route('admin.paiements-bailleurs.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-gray-200 
                          hover:bg-gray-300 text-gray-700 transition">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
