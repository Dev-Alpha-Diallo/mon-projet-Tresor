<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Maison;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    /**
     * Affiche la liste des étudiants
     */
  public function index(Request $request)
{
    $query = Etudiant::query()->with('maison');

     // Filtrer par nom (recherche insensible à la casse)
    if ($request->filled('nom')) {
        $search = $request->nom;
        $query->where(function($q) use ($search) {
            $q->where('nom', 'ILIKE', "%{$search}%"); // ILIKE pour PostgreSQL insensible à la casse
        });
    }

     if ($request->filled('chambre')) {
        $query->where('chambre', $request->chambre);
    }

    // Filtrer par maison
    if ($request->filled('maison_id')) {
        $query->where('maison_id', $request->maison_id);
    }

    // Vérifie si tu veux tout récupérer (GET paramètre 'all=1'), sinon pagination par défaut
    if ($request->filled('all') && $request->all == 1) {
        $etudiants = $query->get(); // récupère tous les étudiants
    } else {
        $etudiants = $query->paginate(100); // par défaut 100 par page, tu peux ajuster
    }

    // Récupérer toutes les maisons pour le select
    $maisons = Maison::all();

    return view('etudiants.index', compact('etudiants', 'maisons'));
}

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $maisons = Maison::all();
        
        return view('etudiants.create', compact('maisons'));
    }

    /**
     * Enregistre un nouvel étudiant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'maison_id' => 'required|exists:maisons,id',
            'chambre' => 'required|string|max:50',
            'loyer_mensuel' => 'required|numeric|min:0',
            'date_debut' => 'required|date',
        ]);

        Etudiant::create($validated);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Etudiant $etudiant)
    {
        $maisons = Maison::all();
        
        return view('etudiants.edit', compact('etudiant', 'maisons'));
    }

    /**
     * Met à jour un étudiant
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'maison_id' => 'required|exists:maisons,id',
            'chambre' => 'required|string|max:50',
            'loyer_mensuel' => 'required|numeric|min:0',
            'date_debut' => 'required|date',
        ]);

        $etudiant->update($validated);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant modifié avec succès.');
    }

    /**
     * Supprime un étudiant
     */
    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    /**
     * Affiche les détails d'un étudiant
     */
    public function show(Etudiant $etudiant)
    {
        $etudiant->load(['maison', 'paiements' => function($query) {
            $query->latest('date_paiement');
        }]);
        
        return view('etudiants.show', compact('etudiant'));
    }
}