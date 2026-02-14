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
        $q = $request->query('q', '');
        $maison = $request->query('maison_id');
        $minScore = 0.60; // Seuil Levenshtein (0-1)

        $query = Etudiant::with('maison')->orderBy('nom');

        // Filtre maison au niveau DB
        if ($maison) {
            $query->where('maison_id', $maison);
        }

        // Récupérer ensemble plus large pour fuzzy filtering en PHP
        $allResults = $query->limit(150)->get();

        // Normaliser la requête UNE FOIS pour comparaison unicode-safe
        if ($q !== '') {
            $q_normalized = mb_strtolower(trim($q), 'UTF-8');

            // Fuzzy match avec approche Unicode-safe
            $allResults = $allResults->filter(function ($e) use ($q_normalized, $minScore) {
                $nom = $e->nom ?? '';
                $maison_name = $e->maison->nom ?? '';
                $chambre = trim($e->chambre ?? '');

                // Normaliser pour comparaison (minuscules, UTF-8 safe)
                $nom_lower = mb_strtolower($nom, 'UTF-8');
                $maison_lower = mb_strtolower($maison_name, 'UTF-8');
                $chambre_lower = mb_strtolower($chambre, 'UTF-8');

                // 1. Recherche par substring simple (case-insensitive)
                if (mb_strpos($nom_lower, $q_normalized) !== false) {
                    return true;
                }
                if (mb_strpos($maison_lower, $q_normalized) !== false) {
                    return true;
                }
                if (mb_strpos($chambre_lower, $q_normalized) !== false) {
                    return true;
                }

                // 2. Distance Levenshtein (tolérer typos)
                if (strlen($q_normalized) >= 2) {
                    $sim = 1.0 - (levenshtein($q_normalized, $nom_lower) / max(strlen($q_normalized), strlen($nom_lower), 1));
                    if ($sim >= $minScore) {
                        return true;
                    }
                }

                return false;
            });

            // Trier par pertinence (débuts d'abord)
            $allResults = $allResults->sort(function ($a, $b) use ($q_normalized) {
                $a_nom = mb_strtolower($a->nom, 'UTF-8');
                $b_nom = mb_strtolower($b->nom, 'UTF-8');
                
                $a_starts = mb_strpos($a_nom, $q_normalized) === 0 ? 1 : 0;
                $b_starts = mb_strpos($b_nom, $q_normalized) === 0 ? 1 : 0;
                
                return $b_starts <=> $a_starts;
            });
        }

        $results = $allResults->limit(20)->map(function ($e) {
            return [
                'id' => $e->id,
                'text' => $e->nom . ' - ' . ($e->maison->nom ?? 'N/A') . ' (Ch ' . $e->chambre . ')'
            ];
        })->values();

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
