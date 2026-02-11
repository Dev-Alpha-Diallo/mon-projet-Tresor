<?php

namespace App\Http\Controllers;

use App\Models\Bailleur;
use Illuminate\Http\Request;

class BailleurController extends Controller
{
    public function index()
    {
        $bailleurs = Bailleur::with('maisons')->latest()->get();
        return view('bailleurs.index', compact('bailleurs'));
    }

    public function create()
    {
        return view('bailleurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
        ]);

        Bailleur::create($validated);

        return redirect()->route('bailleurs.index')
            ->with('success', 'Bailleur ajouté avec succès.');
    }

    public function edit(Bailleur $bailleur)
    {
        return view('bailleurs.edit', compact('bailleur'));
    }

    public function update(Request $request, Bailleur $bailleur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
        ]);

        $bailleur->update($validated);

        return redirect()->route('bailleurs.index')
            ->with('success', 'Bailleur modifié avec succès.');
    }

    public function destroy(Bailleur $bailleur)
    {
        $bailleur->delete();

        return redirect()->route('bailleurs.index')
            ->with('success', 'Bailleur supprimé avec succès.');
    }

    public function show(Bailleur $bailleur)
    {
        $bailleur->load(['maisons', 'paiements' => function($query) {
            $query->latest('date_paiement');
        }]);
        
        return view('bailleurs.show', compact('bailleur'));
    }
}