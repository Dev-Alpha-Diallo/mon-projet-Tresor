<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RapportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function __construct(
        private RapportService $rapportService
    ) {}

    /**
     * Affiche le formulaire de génération de rapport
     */
    public function index()
    {
        // Récupérer les rapports existants
        $fichiers = Storage::files('reports');
        $rapports = collect($fichiers)->map(function($fichier) {
            return [
                'nom' => basename($fichier),
                'chemin' => $fichier,
                'date' => Storage::lastModified($fichier),
                'taille' => Storage::size($fichier),
            ];
        })->sortByDesc('date');

        return view('admin.rapports.index', compact('rapports'));
    }

    /**
     * Génère un rapport (mensuel, trimestriel ou annuel)
     */
    public function generer(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:mensuel,trimestriel,annuel',
            'mois' => 'required_if:type,mensuel|nullable|integer|min:1|max:12',
            'trimestre' => 'required_if:type,trimestriel|nullable|integer|min:1|max:4',
            'annee' => 'required|integer|min:2020|max:2030',
        ]);

        $cheminFichier = match($validated['type']) {
            'mensuel' => $this->rapportService->genererRapportMensuel(
                $validated['mois'],
                $validated['annee']
            ),
            'trimestriel' => $this->rapportService->genererRapportTrimestriel(
                $validated['trimestre'],
                $validated['annee']
            ),
            'annuel' => $this->rapportService->genererRapportAnnuel(
                $validated['annee']
            ),
        };

        return redirect()->route('admin.rapports.index')
            ->with('success', 'Rapport généré avec succès : ' . basename($cheminFichier));
    }

    /**
     * Télécharge un rapport
     */
    public function telecharger(Request $request)
    {
        $fichier = $request->query('fichier');
        
        if (!Storage::exists($fichier)) {
            abort(404);
        }

        return Storage::download($fichier);
    }

    /**
     * Prévisualise un rapport (sans sauvegarder)
     */
    public function previsualiser(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:mensuel,trimestriel,annuel',
            'mois' => 'required_if:type,mensuel|nullable|integer|min:1|max:12',
            'trimestre' => 'required_if:type,trimestriel|nullable|integer|min:1|max:4',
            'annee' => 'required|integer|min:2020|max:2030',
        ]);

        $data = match($validated['type']) {
            'mensuel' => $this->rapportService->collecterDonneesMensuelles(
                $validated['mois'],
                $validated['annee']
            ),
            'trimestriel' => $this->rapportService->collecterDonneesTrimestrielles(
                $validated['trimestre'],
                $validated['annee']
            ),
            'annuel' => $this->rapportService->collecterDonneesAnnuelles(
                $validated['annee']
            ),
        };

        $view = match($validated['type']) {
            'mensuel' => 'admin.rapports.mensuel',
            'trimestriel' => 'admin.rapports.trimestriel',
            'annuel' => 'admin.rapports.annuel',
        };

        return view($view, $data);
    }
}
