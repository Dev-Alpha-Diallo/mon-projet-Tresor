<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Facture;
use App\Models\Maison;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RapportService
{
    /**
     * Génère un rapport mensuel et le sauvegarde en PDF
     *
     * @param int $mois (1-12)
     * @param int $annee
     * @return string Le chemin du fichier généré
     */
    public function genererRapportMensuel(int $mois, int $annee): string
    {
        $data = $this->collecterDonneesMensuelles($mois, $annee);
        
        // Générer le PDF
        $pdf = Pdf::loadView('rapports.mensuel', $data);
        
        // Créer le nom du fichier
        $nomFichier = sprintf('rapport_tresorerie_%04d_%02d.pdf', $annee, $mois);
        $cheminComplet = 'reports/' . $nomFichier;
        
        // Sauvegarder dans storage/app/reports
        Storage::put($cheminComplet, $pdf->output());
        
        return $cheminComplet;
    }

    /**
     * Collecte toutes les données nécessaires pour le rapport mensuel
     *
     * @param int $mois
     * @param int $annee
     * @return array
     */
    private function collecterDonneesMensuelles(int $mois, int $annee): array
    {
        $dateDebut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $dateFin = Carbon::create($annee, $mois, 1)->endOfMonth();

        // Recettes : paiements étudiants du mois
        $paiementsMois = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->get();
        $totalRecettes = $paiementsMois->sum('montant');

        // Dépenses : factures du mois
        $facturesMois = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->get();
        $totalDepenses = $facturesMois->sum('montant');

        // Dépenses par type
        $depensesParType = $facturesMois->groupBy('type')->map(function ($factures) {
            return $factures->sum('montant');
        });

        // Solde début de mois (calculé sur les données jusqu'au mois précédent)
        $soldeDebut = $this->calculerSoldeJusquaDate($dateDebut->copy()->subDay());
        $soldeFin = $soldeDebut + $totalRecettes - $totalDepenses;

        // Étudiants débiteurs et créditeurs
        $etudiants = Etudiant::with('maison')->get();
        $etudiantsDebiteurs = $etudiants->filter(fn($e) => $e->isDebiteur())->sortBy('solde');
        $etudiantsCrediteurs = $etudiants->filter(fn($e) => $e->isCrediteur())->sortByDesc('solde');

        // Situation par maison
        $maisons = Maison::with(['bailleur', 'etudiants'])->get();

        return [
            'mois' => $mois,
            'annee' => $annee,
            'moisNom' => Carbon::create($annee, $mois, 1)->locale('fr')->monthName,
            'dateGeneration' => Carbon::now()->format('d/m/Y H:i'),
            
            // Recettes
            'totalRecettes' => $totalRecettes,
            'paiementsMois' => $paiementsMois,
            
            // Dépenses
            'totalDepenses' => $totalDepenses,
            'facturesMois' => $facturesMois,
            'depensesParType' => $depensesParType,
            
            // Synthèse
            'soldeDebut' => $soldeDebut,
            'soldeFin' => $soldeFin,
            'excedentDeficit' => $soldeFin - $soldeDebut,
            
            // Étudiants
            'etudiantsDebiteurs' => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
            
            // Maisons
            'maisons' => $maisons,
        ];
    }

    /**
     * Calcule le solde de la caisse jusqu'à une date donnée
     *
     * @param Carbon $date
     * @return float
     */
    private function calculerSoldeJusquaDate(Carbon $date): float
    {
        $totalRecettes = Paiement::where('date_paiement', '<=', $date)->sum('montant');
        $totalDepenses = Facture::where('date_paiement', '<=', $date)->sum('montant');
        
        return $totalRecettes - $totalDepenses;
    }

    /**
     * Récupère les données pour le tableau de bord
     *
     * @return array
     */
    public function getDonneesDashboard(): array
    {
        $etudiants = Etudiant::with('maison')->get();
        $maisons = Maison::with(['bailleur', 'etudiants'])->get();
        
        $totalRecettes = Paiement::sum('montant');
        $totalDepenses = Facture::sum('montant');
        $soldeCaisse = $totalRecettes - $totalDepenses;
        
        $etudiantsDebiteurs = $etudiants->filter(fn($e) => $e->isDebiteur());
        $etudiantsCrediteurs = $etudiants->filter(fn($e) => $e->isCrediteur());
        
        return [
            'soldeCaisse' => $soldeCaisse,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'nombreEtudiants' => $etudiants->count(),
            'nombreDebiteurs' => $etudiantsDebiteurs->count(),
            'nombreCrediteurs' => $etudiantsCrediteurs->count(),
            'totalDettes' => abs($etudiantsDebiteurs->sum('solde')),
            'totalAvances' => $etudiantsCrediteurs->sum('solde'),
            'maisons' => $maisons,
        ];
    }
}