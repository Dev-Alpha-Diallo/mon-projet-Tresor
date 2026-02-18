<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Maison;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    /**
     * Affiche la liste des étudiants
     */
    public function index()
    {
        $query = Etudiant::with('maison')->latest();

        // Filtres depuis la requête (case-insensitive pour nom)
        if(request()->filled('nom')){
            $nom = request('nom');
            // Utiliser ILIKE en PostgreSQL (ou LIKE avec COLLATE en MySQL)
            $query->whereRaw('LOWER(nom) LIKE LOWER(?)', ['%' . $nom . '%']);
        }

        if(request()->filled('chambre')){
            $query->where('chambre', request('chambre'));
        }

        if(request()->filled('maison_id')){
            $query->where('maison_id', request('maison_id'));
        }

        $etudiants = $query->get();
        $maisons = \App\Models\Maison::all();

        return view('admin.etudiants.index', compact('etudiants', 'maisons'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $maisons = Maison::all();
        
        return view('admin.etudiants.create', compact('maisons'));
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

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Etudiant $etudiant)
    {
        $maisons = Maison::all();
        
        return view('admin.etudiants.edit', compact('etudiant', 'maisons'));
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

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant modifié avec succès.');
    }

    /**
     * Supprime un étudiant
     */
    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();

        return redirect()->route('admin.etudiants.index')
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
        
        return view('admin.etudiants.show', compact('etudiant'));
    }

    /**
     * Recherche floue (fuzzy) JSON pour selects asynchrones
     * Tolère les fautes de frappe, casse et diacritiques
     */
     public function search(Request $request)
    {
        $q = $request->get('q', '');
        
        $query = Etudiant::with('maison');
        
        if (strlen($q) > 0) {
            $query->where(function($query) use ($q) {
                $query->where('nom', 'ILIKE', "%{$q}%")
                      ->orWhere('chambre', 'ILIKE', "%{$q}%");
            });
        }
        
        $etudiants = $query->orderBy('nom')->limit(50)->get();

        $results = $etudiants->map(function($e) {
            return [
                'id' => $e->id,
                'nom' => $e->nom,
                'maison' => $e->maison ? $e->maison->nom : 'N/A',
                'chambre' => $e->chambre,
                'solde' => $e->solde ?? 0,
            ];
        });

        return response()->json($results);
    }

    /**
     * Exporte la liste de tous les étudiants en PDF
     */
    public function exportTous(Request $request)
    {
        $tri = $request->query('tri', 'nom');
        
        $etudiants = Etudiant::with(['maison.bailleur'])->get();
        
        // Trier en fonction du paramètre
        if ($tri === 'maison') {
            // Grouper par maison, puis trier par maison et nom
            $etudiants = $etudiants->sortBy(function ($etudiant) {
                return $etudiant->maison->nom . '|' . $etudiant->nom;
            })->values();
            $titre = 'Liste des étudiants (triée par maison)';
        } else {
            // Trier par nom (défaut)
            $etudiants = $etudiants->sortBy('nom')->values();
            $titre = 'Liste de tous les étudiants';
        }

        $data = [
            'titre' => $titre,
            'etudiants' => $etudiants,
            'nombreTotal' => $etudiants->count(),
            'dateGeneration' => now()->format('d/m/Y à H:i'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadview('admin.etudiants.pdf.liste-complete', $data);
        
        return $pdf->download('liste_etudiants_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Exporte la liste des étudiants débiteurs en PDF
     */
    public function exportDebiteurs()
    {
        $etudiants = Etudiant::with(['maison.bailleur'])
            ->get()
            ->filter(fn($e) => $e->solde < 0)
            ->sortBy('solde');

        $totalDettes = abs($etudiants->sum('solde'));

        $data = [
            'titre' => 'Liste des étudiants débiteurs',
            'etudiants' => $etudiants,
            'nombreTotal' => $etudiants->count(),
            'totalDettes' => $totalDettes,
            'dateGeneration' => now()->format('d/m/Y à H:i'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadview('admin.etudiants.pdf.liste-debiteurs', $data);
        
        return $pdf->download('liste_debiteurs_' . date('Y-m-d') . '.pdf');
    }
}
