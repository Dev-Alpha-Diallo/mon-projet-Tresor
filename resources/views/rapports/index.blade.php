@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Rapports mensuels</h1>

    <!-- Formulaire de génération -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Générer un nouveau rapport</h2>
        
        <form action="{{ route('rapports.generer') }}" method="POST" class="flex items-end gap-4">
            @csrf
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mois</label>
                <select name="mois" required class="w-full px-4 py-2 border rounded-lg">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->locale('fr')->monthName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                <select name="annee" required class="w-full px-4 py-2 border rounded-lg">
                    @foreach(range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    📄 Générer PDF
                </button>
                
                <button type="submit" formaction="{{ route('rapports.previsualiser') }}" formmethod="GET" 
                    formtarget="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    👁️ Prévisualiser
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des rapports existants -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-gray-800">Rapports générés</h2>
        </div>
        
        <div class="p-6">
            @if($rapports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom du fichier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date de création</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taille</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rapports as $rapport)
                            <tr>
                                <td class="px-6 py-4 font-medium">
                                    {{ $rapport['nom'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::createFromTimestamp($rapport['date'])->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ number_format($rapport['taille'] / 1024, 2) }} KB
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('rapports.telecharger', ['fichier' => $rapport['chemin']]) }}" 
                                       class="text-blue-600 hover:underline">
                                        ⬇️ Télécharger
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Aucun rapport généré pour le moment.</p>
                    <p class="text-sm mt-2">Utilisez le formulaire ci-dessus pour générer votre premier rapport.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection