@extends('layouts.app')

@section('title', 'Modifier Bailleur')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">Modifier le Bailleur</h1>

        <form action="{{ route('admin.bailleurs.update', $bailleur) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $bailleur->nom) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition duration-200">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $bailleur->telephone) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition duration-200">
                @error('telephone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('admin.bailleurs.index') }}"
                   class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition duration-200">
                   Annuler
                </a>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold shadow-lg transition duration-200">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
