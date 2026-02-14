<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Paiement;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Affiche la liste des paiements
     */
   public function index(Request $request)
{
    $query = Paiement::query()->with('etudiant.maison');

    // Filtrer par nom d'étudiant
    if ($request->filled('nom')) {
        $query->whereHas('etudiant', function ($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->nom . '%');
        });
    }

    // Filtrer par maison
    if ($request->filled('maison_id')) {
        $query->whereHas('etudiant', function ($q) use ($request) {
            $q->where('maison_id', $request->maison_id);
        });
    }

    $paiements = $query->orderBy('date_paiement', 'desc')->paginate(20);

    $maisons = Maison::all();

    return view('admin.paiements.index', compact('paiements', 'maisons'));
}

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        // Ne pas charger tous les étudiants (potentiellement très volumineux).
        // Si une valeur précédemment sélectionnée existe, la récupérer pour préremplir.
        $selected = null;
        if (old('etudiant_id')) {
            $selected = Etudiant::with('maison')->find(old('etudiant_id'));
        }

        return view('admin.paiements.create', compact('selected'));
    }

    /**
     * Enregistre un nouveau paiement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'montant' => 'required|numeric|min:0.01|max:999999.99',
            'date_paiement' => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:especes,mobile_money,virement',
            'remarque' => 'nullable|string|max:500',
        ]);

        // Validation : La date de paiement ne doit pas être avant l'inscription
        $etudiant = Etudiant::findOrFail($validated['etudiant_id']);
        if ($validated['date_paiement'] < $etudiant->created_at->format('Y-m-d')) {
            return back()->withInput()->withErrors([
                'date_paiement' => 'La date de paiement ne peut pas être avant l\'inscription de l\'étudiant.',
            ]);
        }

        Paiement::create($validated);

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Paiement $paiement)
    {
        // Préremplir l'étudiant lié au paiement sans charger toute la table
        $selected = $paiement->etudiant()->with('maison')->first();

        return view('admin.paiements.edit', compact('paiement', 'selected'));
    }

    /**
     * Met à jour un paiement
     */
    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'montant' => 'required|numeric|min:0.01|max:999999.99',
            'date_paiement' => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:especes,mobile_money,virement',
            'remarque' => 'nullable|string|max:500',
        ]);

        $paiement->update($validated);

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement modifié avec succès.');
    }

    /**
     * Supprime un paiement
     */
    public function destroy(Paiement $paiement)
    {
        $paiement->delete();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}
