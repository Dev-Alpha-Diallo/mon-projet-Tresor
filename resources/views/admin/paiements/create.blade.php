@extends('layouts.app')

@section('title', 'Enregistrer un paiement')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Enregistrer un paiement</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.paiements.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Étudiant -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Étudiant <span class="text-red-500">*</span></label>
                    
                    <input type="hidden" name="etudiant_id" id="etudiant_id" value="{{ old('etudiant_id') }}" required>
                    
                    <input 
                        id="search" 
                        type="text" 
                        placeholder="Cliquez pour voir la liste ou tapez pour rechercher..." 
                        autocomplete="off"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    
                    <div id="list" class="absolute z-50 w-full bg-white border rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto"></div>
                    
                    @error('etudiant_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="montant" value="{{ old('montant') }}" required min="0" step="1"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('montant')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ old('date_paiement', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('date_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Moyen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Moyen de paiement</label>
                    <select name="moyen_paiement" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="especes">Espèces</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="virement">Virement</option>
                    </select>
                    @error('moyen_paiement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remarque -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Remarque (optionnel)</label>
                    <textarea name="remarque" rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('remarque') }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Enregistrer
                </button>
                <a href="{{ route('admin.paiements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('search');
    const hidden = document.getElementById('etudiant_id');
    const list = document.getElementById('list');
    let timer, data = [];

    function show(items) {
        if(!items.length) {
            list.innerHTML = '<div class="p-3 text-gray-500">Aucun</div>';
        } else {
            list.innerHTML = items.map(i => 
                '<div class="p-3 hover:bg-blue-50 cursor-pointer border-b" data-id="'+i.id+'">'+
                '<div class="font-bold">'+i.nom+'</div>'+
                '<div class="text-sm text-gray-600">'+i.maison+' - Ch '+i.chambre+'</div>'+
                '</div>'
            ).join('');
        }
        list.classList.remove('hidden');
    }

    function load(q) {
        fetch("{{ route('admin.etudiants.search') }}?q="+encodeURIComponent(q))
            .then(r => r.json())
            .then(d => { data = d; show(d); })
            .catch(e => {
                console.error(e);
                list.innerHTML = '<div class="p-3 text-red-500">Erreur</div>';
                list.classList.remove('hidden');
            });
    }

    input.addEventListener('focus', () => data.length ? show(data) : load(''));
    input.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => load(this.value.trim()), 300);
    });

    list.addEventListener('click', function(e) {
        const el = e.target.closest('[data-id]');
        if(el) {
            hidden.value = el.dataset.id;
            input.value = el.querySelector('.font-bold').textContent;
            list.classList.add('hidden');
        }
    });

    document.addEventListener('click', e => {
        if(!input.contains(e.target) && !list.contains(e.target)) list.classList.add('hidden');
    });
});
</script>
@endsection