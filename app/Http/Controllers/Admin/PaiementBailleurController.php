<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaiementBailleur;
use App\Models\Bailleur;
use App\Models\Maison;
use Illuminate\Http\Request;

class PaiementBailleurController extends Controller
{
    /**
     * Affiche la liste des paiements aux bailleurs
     */
   public function index()
{
    $paiementsBailleurs = PaiementBailleur::with(['bailleur', 'maison'])->orderBy('date_paiement', 'desc')->paginate(20);
    return view('admin.paiements_bailleurs.index', compact('paiementsBailleurs'));
}


    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $bailleurs = Bailleur::orderBy('nom')->get();
        $maisons = Maison::orderBy('nom')->get();

        return view('admin.paiements_bailleurs.create', compact('bailleurs', 'maisons'));
    }

    /**
     * Enregistre un nouveau paiement au bailleur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bailleur_id' => 'required|exists:bailleurs,id',
            'maison_id' => 'required|exists:maisons,id',
            'montant' => 'required|numeric|min:0.01',
            'date_paiement' => 'required|date',
            'remarque' => 'nullable|string|max:500',
        ]);

        // Vérifier que la maison appartient bien au bailleur
        $maison = Maison::findOrFail($validated['maison_id']);
        if ($maison->bailleur_id != $validated['bailleur_id']) {
            return back()->withInput()->withErrors([
                'maison_id' => 'Cette maison n\'appartient pas à ce bailleur.',
            ]);
        }

        PaiementBailleur::create($validated);

        return redirect()->route('admin.paiements-bailleurs.index')
            ->with('success', 'Paiement au bailleur enregistré avec succès.');
    }

    /**
     * Affiche le formulaire d'édition
     */
   public function edit($id)
{
    $paiementBailleur = PaiementBailleur::with(['bailleur', 'maison'])->findOrFail($id);
    $bailleurs = Bailleur::orderBy('nom')->get();
    $maisons = Maison::orderBy('nom')->get();

    return view('admin.paiements_bailleurs.edit', compact('paiementBailleur', 'bailleurs', 'maisons'));
}


    /**
     * Met à jour un paiement au bailleur
     */
    public function update(Request $request, PaiementBailleur $paiementBailleur)
    {
        $validated = $request->validate([
            'bailleur_id' => 'required|exists:bailleurs,id',
            'maison_id' => 'required|exists:maisons,id',
            'montant' => 'required|numeric|min:0.01',
            'date_paiement' => 'required|date',
            'remarque' => 'nullable|string|max:500',
        ]);

        // Vérifier que la maison appartient bien au bailleur
        $maison = Maison::findOrFail($validated['maison_id']);
        if ($maison->bailleur_id != $validated['bailleur_id']) {
            return back()->withInput()->withErrors([
                'maison_id' => 'Cette maison n\'appartient pas à ce bailleur.',
            ]);
        }

        $paiementBailleur->update($validated);

        return redirect()->route('admin.paiements-bailleurs.index')
            ->with('success', 'Paiement au bailleur modifié avec succès.');
    }

    /**
     * Supprime un paiement au bailleur
     */
    public function destroy(PaiementBailleur $paiementBailleur)
    {
        $paiementBailleur->delete();

        return redirect()->route('admin.paiements-bailleurs.index')
            ->with('success', 'Paiement au bailleur supprimé avec succès.');
    }

    /**
     * Affiche les détails d'un paiement
     */
    public function show(PaiementBailleur $paiementBailleur)
    {
        $paiementBailleur->load(['bailleur', 'maison']);

        return view('admin.paiements-bailleurs.show', compact('paiementBailleur'));
    }
}
