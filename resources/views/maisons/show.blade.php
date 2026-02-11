@extends('layouts.app')

@section('title', $maison->nom)

@section('content')
<div class="p-4">
    <div class="mb-6">
        <a href="{{ route('maisons.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
            ← Retour à la liste
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">{{ $maison->nom }}</h1>
    </div>

    <div class="bg-white rounded-lg border shadow-sm p-4">
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">Adresse</p>
                <p class="text-gray-900">{{ $maison->adresse }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Bailleur</p>
                <p class="text-gray-900">{{ $maison->bailleur->nom }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Loyer total mensuel</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($maison->loyer_total_mensuel, 0, ',', ' ') }} FCFA</p>
            </div>
            
            <div class="pt-4 border-t">
                <div class="flex justify-between">
                    <a href="{{ route('maisons.edit', $maison) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection