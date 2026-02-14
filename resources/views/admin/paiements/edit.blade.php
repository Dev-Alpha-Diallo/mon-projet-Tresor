@extends('layouts.app')

@section('title', 'Modifier un paiement')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modifier un paiement</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.paiements.update', $paiement) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Étudiant (recherche asynchrone) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Étudiant</label>
                    <input type="hidden" name="etudiant_id" id="etudiant_id" value="{{ old('etudiant_id', $selected->id ?? '') }}">
                    <input id="etudiant_search" type="text" placeholder="Rechercher un étudiant..." value="{{ old('etudiant_name', $selected ? ($selected->nom . ' - ' . ($selected->maison->nom ?? '') . ' (Ch ' . $selected->chambre . ')') : '') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <div id="etudiant_suggestions" class="border rounded bg-white mt-1 hidden z-50"></div>
                    @error('etudiant_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="montant" value="{{ old('montant', $paiement->montant) }}" required min="0" step="1"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('montant')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ old('date_paiement', $paiement->date_paiement->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('date_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Moyen de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Moyen de paiement</label>
                    <select name="moyen_paiement" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="especes" {{ old('moyen_paiement', $paiement->moyen_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="mobile_money" {{ old('moyen_paiement', $paiement->moyen_paiement) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="virement" {{ old('moyen_paiement', $paiement->moyen_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                    </select>
                    @error('moyen_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remarque -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Remarque (optionnel)</label>
                    <textarea name="remarque" rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('remarque', $paiement->remarque) }}</textarea>
                    @error('remarque')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="submit" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                    Mettre à jour
                </button>
                <a href="{{ route('admin.paiements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const searchInput = document.getElementById('etudiant_search');
    const hidden = document.getElementById('etudiant_id');
    const suggestions = document.getElementById('etudiant_suggestions');
    const url = "{{ route('admin.etudiants.search') }}";

    let debounceTimer = null;

    function renderList(items){
        if(!items.length){ suggestions.classList.add('hidden'); suggestions.innerHTML=''; return; }
        suggestions.innerHTML = items.map(i => `<div class="px-3 py-2 hover:bg-gray-100 cursor-pointer" data-id="${i.id}">${i.text}</div>`).join('');
        suggestions.classList.remove('hidden');
    }

    searchInput.addEventListener('input', function(e){
        const q = this.value.trim();
        clearTimeout(debounceTimer);
        if(q.length < 1){ suggestions.classList.add('hidden'); hidden.value=''; return; }
        debounceTimer = setTimeout(()=>{
            fetch(url + '?q=' + encodeURIComponent(q), {headers:{'X-Requested-With':'XMLHttpRequest'}})
                .then(r => r.json())
                .then(data => renderList(data))
                .catch(()=>{ suggestions.classList.add('hidden'); });
        }, 250);
    });

    suggestions.addEventListener('click', function(e){
        const el = e.target.closest('[data-id]');
        if(!el) return;
        hidden.value = el.getAttribute('data-id');
        searchInput.value = el.textContent.trim();
        suggestions.classList.add('hidden');
    });
})();
</script>
@endpush

@endsection
