<?php

namespace App\Http\Controllers;

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

        return view('rapports.index', compact('rapports'));
    }

    /**
     * Génère un rapport mensuel
     */
    public function generer(Request $request)
    {
        $validated = $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030',
        ]);

        $cheminFichier = $this->rapportService->genererRapportMensuel(
            $validated['mois'],
            $validated['annee']
        );

        return redirect()->route('rapports.index')
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
     * Prévisualise un rapport mensuel (sans sauvegarder)
     */
    public function previsualiser(Request $request)
    {
        $validated = $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030',
        ]);

        // Utiliser une méthode privée pour obtenir les données
        $reflection = new \ReflectionClass($this->rapportService);
        $method = $reflection->getMethod('collecterDonneesMensuelles');
        $method->setAccessible(true);
        
        $data = $method->invoke(
            $this->rapportService,
            $validated['mois'],
            $validated['annee']
        );

        return view('rapports.mensuel', $data);
    }
}