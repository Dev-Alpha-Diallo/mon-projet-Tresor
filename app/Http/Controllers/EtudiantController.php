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
    public function index()
    {
        $etudiants = Etudiant::with('maison')->latest()->get();
        $maisons = \App\Models\Maison::all();
        
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

    /**
     * Exporte la liste de tous les étudiants en PDF
     */
    public function exportTous()
    {
        $etudiants = Etudiant::with(['maison.bailleur'])
            ->orderBy('nom')
            ->get();

        $data = [
            'titre' => 'Liste de tous les étudiants',
            'etudiants' => $etudiants,
            'nombreTotal' => $etudiants->count(),
            'dateGeneration' => now()->format('d/m/Y à H:i'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('etudiants.pdf.liste-complete', $data);
        
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('etudiants.pdf.liste-debiteurs', $data);
        
        return $pdf->download('liste_debiteurs_' . date('Y-m-d') . '.pdf');
    }
}