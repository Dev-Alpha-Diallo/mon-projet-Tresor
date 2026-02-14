<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Bailleur;
use Illuminate\Http\Request;

class MaisonController extends Controller
{
    public function index()
    {
        $maisons = Maison::with('bailleur')->latest()->paginate(20);
        return view('admin.maisons.index', compact('maisons'));
    }

    public function create()
    {
        $bailleurs = Bailleur::all();
        return view('admin.maisons.create', compact('bailleurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'bailleur_id' => 'required|exists:bailleurs,id',
            'loyer_total_mensuel' => 'required|numeric|min:0',
        ]);

        Maison::create($validated);

        return redirect()->route('admin.maisons.index')
            ->with('success', 'Maison ajoutée avec succès.');
    }

    public function edit(Maison $maison)
    {
        $bailleurs = Bailleur::all();
        return view('admin.maisons.edit', compact('maison', 'bailleurs'));
    }

    public function update(Request $request, Maison $maison)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'bailleur_id' => 'required|exists:bailleurs,id',
            'loyer_total_mensuel' => 'required|numeric|min:0',
        ]);

        $maison->update($validated);

        return redirect()->route('admin.maisons.index')
            ->with('success', 'Maison modifiée avec succès.');
    }

    public function destroy(Maison $maison)
    {
        $maison->delete();

        return redirect()->route('admin.maisons.index')
            ->with('success', 'Maison supprimée avec succès.');
    }

    public function show(Maison $maison)
    {
        $maison->load(['bailleur', 'etudiants', 'factures' => function($query) {
            $query->latest('date_paiement');
        }]);
        
        return view('admin.maisons.show', compact('maison'));
    }
}
