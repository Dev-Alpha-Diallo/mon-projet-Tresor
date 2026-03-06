@extends('layouts.app')
@section('title', 'Modifier Facture #' . str_pad($facture->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.factures.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-white transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Retour aux factures
        </a>
        <h1 class="text-2xl font-bold text-white">
            Modifier Facture #{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}
        </h1>
        <p class="text-slate-400 mt-1 text-sm">{{ $facture->maison->nom ?? 'Maison inconnue' }}</p>
    </div>

    <div class="rounded-2xl p-6" style="background:#1e293b; border:1px solid rgba(99,102,241,0.15);">
        {{-- ✅ UN SEUL form --}}
        <form action="{{ route('admin.factures.update', $facture) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Statut --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['impayee' => '🔴 Impayée', 'partiel' => '🟡 Partielle', 'payee' => '🟢 Payée'] as $val => $label)
                            <label class="flex items-center gap-2 px-4 py-2 rounded-xl cursor-pointer transition"
                                style="background:rgba(255,255,255,0.04); border:1px solid rgba(99,102,241,0.15);">
                                <input type="radio" name="statut" value="{{ $val }}" id="statut_{{ $val }}"
                                    {{ old('statut', $facture->statut) == $val ? 'checked' : '' }}
                                    class="text-indigo-500 focus:ring-indigo-500">
                                <span class="text-sm text-slate-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Maison --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Maison <span class="text-red-400">*</span></label>
                    <select name="maison_id" required class="w-full rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                        @foreach($maisons as $maison)
                            <option value="{{ $maison->id }}" {{ old('maison_id', $facture->maison_id) == $maison->id ? 'selected' : '' }}>
                                {{ $maison->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('maison_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Numéro facture --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Numéro de facture <span class="text-red-400">*</span></label>
                    <input type="text" name="numero_facture"
                        value="{{ old('numero_facture', $facture->numero_facture) }}" required
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                    @error('numero_facture') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                    <select name="type" required class="w-full rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $facture->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Montant --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Montant (FCFA) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400 text-sm font-medium">F</span>
                        <input type="number" name="montant" required min="0"
                            value="{{ old('montant', $facture->montant) }}"
                            class="w-full rounded-xl pl-8 pr-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                    </div>
                    @error('montant') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Date émission --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Date d'émission <span class="text-red-400">*</span></label>
                    <input type="date" name="date_emission" required
                        value="{{ old('date_emission', $facture->date_emission?->format('Y-m-d')) }}"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                    @error('date_emission') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Date échéance --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Date d'échéance <span class="text-red-400">*</span></label>
                    <input type="date" name="date_echeance" required
                        value="{{ old('date_echeance', $facture->date_echeance?->format('Y-m-d')) }}"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                    @error('date_echeance') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Date paiement --}}
                <div id="champ_date_paiement" class="{{ old('statut', $facture->statut) == 'impayee' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Date de paiement effectif</label>
                    <input type="date" name="date_paiement"
                        value="{{ old('date_paiement', $facture->date_paiement?->format('Y-m-d')) }}"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">
                    <p class="text-xs text-slate-500 mt-1">Laisser vide si pas encore payée</p>
                    @error('date_paiement') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">{{ old('description', $facture->description) }}</textarea>
                </div>

                {{-- Remarques --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Remarques internes</label>
                    <textarea name="remarques" rows="2"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                        style="background:#0f172a; border:1px solid rgba(99,102,241,0.2);">{{ old('remarques', $facture->remarques) }}</textarea>
                </div>

            </div>

            {{-- Boutons --}}
            <div class="flex items-center justify-between mt-6 pt-5 border-t border-white/5">

                {{-- ✅ Bouton suppression HORS du form principal --}}
                <button type="button" onclick="document.getElementById('form-delete').submit()"
                    class="flex items-center gap-2 text-sm text-red-400 hover:text-red-300 transition px-4 py-2.5 rounded-xl hover:bg-red-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                    Supprimer
                </button>

                <div class="flex gap-3">
                    <a href="{{ route('admin.factures.index') }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white transition"
                        style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                        style="background:linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>

        {{-- ✅ Form suppression séparé --}}
        <form id="form-delete" action="{{ route('admin.factures.destroy', $facture) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
    // Afficher/cacher date paiement selon statut (radio)
    document.querySelectorAll('input[name="statut"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const champ = document.getElementById('champ_date_paiement');
            champ.classList.toggle('hidden', this.value === 'impayee');
        });
    });

    // Confirmation suppression
    document.getElementById('form-delete').addEventListener('submit', function(e) {
        if (!confirm('Supprimer cette facture définitivement ?')) e.preventDefault();
    });
</script>
@endsection