<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bailleur;
use Illuminate\Http\Request;

class BailleurController extends Controller
{
    public function index()
    {
        $bailleurs = Bailleur::with('maisons')->latest()->paginate(20);
        return view('admin.bailleurs.index', compact('bailleurs'));
    }

    public function create()
    {
        return view('admin.bailleurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
        ]);

        Bailleur::create($validated);

        return redirect()->route('admin.bailleurs.index')
            ->with('success', 'Bailleur ajouté avec succès.');
    }

    public function edit(Bailleur $bailleur)
    {
        return view('admin.bailleurs.edit', compact('bailleur'));
    }

    public function update(Request $request, Bailleur $bailleur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:50',
        ]);

        $bailleur->update($validated);

        return redirect()->route('admin.bailleurs.index')
            ->with('success', 'Bailleur modifié avec succès.');
    }

    public function destroy(Bailleur $bailleur)
    {
        $bailleur->delete();

        return redirect()->route('admin.bailleurs.index')
            ->with('success', 'Bailleur supprimé avec succès.');
    }

    public function show(Bailleur $bailleur)
    {
        $bailleur->load(['maisons', 'paiements' => function($query) {
            $query->latest('date_paiement');
        }]);
        
        return view('admin.bailleurs.show', compact('bailleur'));
    }
}
