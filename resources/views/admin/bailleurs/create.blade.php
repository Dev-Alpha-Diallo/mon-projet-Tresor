@extends('layouts.app')

@section('title', 'Ajouter un bailleur')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800"> Ajouter un bailleur</h1>
            </div>
            <a href="{{ route('admin.bailleurs.index') }}" 
               class="flex items-center space-x-2 px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                ← Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('admin.bailleurs.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input type="text" name="nom" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           required>
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="tel" name="telephone" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                <a href="{{ route('admin.bailleurs.index') }}" 
                   class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" 
                       class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
