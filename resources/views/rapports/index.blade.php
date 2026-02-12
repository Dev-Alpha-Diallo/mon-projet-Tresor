@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Rapports de trésorerie</h1>
        <p class="mt-1 text-sm text-gray-500">Générez vos rapports mensuels, trimestriels ou annuels</p>
    </div>

    <!-- Formulaire -->
    <div class="glass-effect rounded-2xl p-6 border border-gray-200/50">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Générer un nouveau rapport</h2>
        
        <form action="{{ route('rapports.generer') }}" method="POST" id="rapportForm" class="space-y-4">
            @csrf
            
            <!-- Type de rapport -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de rapport</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                        <input type="radio" name="type" value="mensuel" checked class="mr-3" onchange="updateFormFields()">
                        <div>
                            <div class="font-medium text-gray-900">Mensuel</div>
                            <div class="text-xs text-gray-500">Un mois spécifique</div>
                        </div>
                    </label>
                    
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                        <input type="radio" name="type" value="trimestriel" class="mr-3" onchange="updateFormFields()">
                        <div>
                            <div class="font-medium text-gray-900">Trimestriel</div>
                            <div class="text-xs text-gray-500">3 mois consécutifs</div>
                        </div>
                    </label>
                    
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                        <input type="radio" name="type" value="annuel" class="mr-3" onchange="updateFormFields()">
                        <div>
                            <div class="font-medium text-gray-900">Annuel</div>
                            <div class="text-xs text-gray-500">Mandat complet</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Mois -->
                <div id="moisField">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mois</label>
                    <select name="mois" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->locale('fr')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Trimestre -->
                <div id="trimestreField" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trimestre</label>
                    <select name="trimestre" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="1">T1 (Janvier - Mars)</option>
                        <option value="2">T2 (Avril - Juin)</option>
                        <option value="3">T3 (Juillet - Septembre)</option>
                        <option value="4">T4 (Octobre - Décembre)</option>
                    </select>
                </div>

                <!-- Année -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                    <select name="annee" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2.5 rounded-lg font-medium transition-all shadow-md hover:shadow-lg">
                        📄 Générer PDF
                    </button>
                    
                    <button type="submit" formaction="{{ route('rapports.previsualiser') }}" formmethod="GET" formtarget="_blank" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium transition-all">
                        👁️ Prévisualiser
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des rapports -->
    <div class="glass-effect rounded-2xl border border-gray-200/50 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b">
            <h2 class="text-lg font-bold text-gray-900">Rapports générés ({{ $rapports->count() }})</h2>
        </div>
        
        @if($rapports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Fichier</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase">Taille</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-900 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rapports as $rapport)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-sm text-gray-900">{{ $rapport['nom'] }}</td>
                            <td class="px-6 py-4">
                                @if(str_contains($rapport['nom'], 'trimestriel'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Trimestriel</span>
                                @elseif(str_contains($rapport['nom'], 'annuel'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Annuel</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Mensuel</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ \Carbon\Carbon::createFromTimestamp($rapport['date'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($rapport['taille'] / 1024, 2) }} KB</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('rapports.telecharger', ['fichier' => $rapport['chemin']]) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition text-sm font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Télécharger
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rapport</h3>
                <p class="mt-1 text-sm text-gray-500">Générez votre premier rapport ci-dessus.</p>
            </div>
        @endif
    </div>
</div>

<script>
function updateFormFields() {
    const type = document.querySelector('input[name="type"]:checked').value;
    const moisField = document.getElementById('moisField');
    const trimestreField = document.getElementById('trimestreField');
    
    moisField.style.display = type === 'mensuel' ? 'block' : 'none';
    trimestreField.style.display = type === 'trimestriel' ? 'block' : 'none';
    
    // Désactiver les champs non utilisés
    moisField.querySelector('select').disabled = type !== 'mensuel';
    trimestreField.querySelector('select').disabled = type !== 'trimestriel';
}
</script>
@endsection