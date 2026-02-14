<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Facture;
use App\Models\Maison;
use App\Models\Paiement;
use App\Models\PaiementBailleur;
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
        $pdf = Pdf::loadView('admin.rapports.mensuel', $data);
        
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
    public function collecterDonneesMensuelles(int $mois, int $annee): array
    {
        $dateDebut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $dateFin = Carbon::create($annee, $mois, 1)->endOfMonth();

        // Recettes : utiliser agrégation SQL pour éviter de charger toutes les lignes
        $totalRecettes = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Dépenses : agrégation SQL
        $totalDepenses = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');
        
        // Paiements bailleurs
        $totalPaiementsBailleurs = PaiementBailleur::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Dépenses par type (clé => montant)
        $depensesParType = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->selectRaw('type, SUM(montant) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        // Solde début de mois (calculé sur les données jusqu'au mois précédent)
        $soldeDebut = $this->calculerSoldeJusquaDate($dateDebut->copy()->subDay());
        $soldeFin = $soldeDebut + $totalRecettes - $totalDepenses - $totalPaiementsBailleurs;

        // Calculer statistiques d'étudiants via helper (streaming interne)
        $stats = $this->computeEtudiantsStats(1000, 1000);
        $nombreEtudiants = $stats['nombreEtudiants'];
        $nombreDebiteurs = $stats['nombreDebiteurs'];
        $nombreCrediteurs = $stats['nombreCrediteurs'];
        $totalDettes = $stats['totalDettes'];
        $totalAvances = $stats['totalAvances'];
        $etudiantsDebiteurs = $stats['etudiantsDebiteurs'];
        $etudiantsCrediteurs = $stats['etudiantsCrediteurs'];

        // Situation par maison : charger relations nécessaires et compter étudiants (maisons attendues petites)
        $maisons = Maison::with('bailleur')
            ->withCount('etudiants')
            ->get();

        return [
            'mois' => $mois,
            'annee' => $annee,
            'moisNom' => Carbon::create($annee, $mois, 1)->locale('fr')->monthName,
            'dateGeneration' => Carbon::now()->format('d/m/Y H:i'),
            
            // Recettes
            'totalRecettes' => $totalRecettes,
            // Pour la liste détaillée dans le PDF on récupère un jeu limité de colonnes
            'paiementsMois' => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('etudiant')
                                ->orderBy('date_paiement')
                                ->get(['id','etudiant_id','montant','date_paiement']),
            
            // Dépenses
            'totalDepenses' => $totalDepenses,
            'facturesMois' => Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('maison')
                                ->orderBy('date_paiement')
                                ->get(['id','maison_id','montant','date_paiement','type']),
            'depensesParType' => $depensesParType,
            
            // Paiements bailleurs
            'totalPaiementsBailleurs' => $totalPaiementsBailleurs,
            'paiementsBailleursMois' => PaiementBailleur::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('maison')
                                ->orderBy('date_paiement')
                                ->get(['id', 'maison_id', 'montant', 'date_paiement']),
            
            // Synthèse
            'soldeDebut' => $soldeDebut,
            'soldeFin' => $soldeFin,
            'excedentDeficit' => $soldeFin - $soldeDebut,
            
            // Étudiants
            'etudiantsDebiteurs' => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
            'nombreEtudiants' => $nombreEtudiants,
            'nombreDebiteurs' => $nombreDebiteurs,
            'nombreCrediteurs' => $nombreCrediteurs,
            'totalDettes' => abs($totalDettes),
            'totalAvances' => $totalAvances,
            
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
        // Synthèses rapides sans charger toutes les entités
        $totalRecettes = Paiement::sum('montant');
        $totalDepenses = Facture::sum('montant');
        $soldeCaisse = $totalRecettes - $totalDepenses;

        // Calculer stats d'étudiants (top 20 pour dashboard)
        $stats = $this->computeEtudiantsStats(20, 20, 20);
        $nombreEtudiants = $stats['nombreEtudiants'];
        $nombreDebiteurs = $stats['nombreDebiteurs'];
        $nombreCrediteurs = $stats['nombreCrediteurs'];
        $etudiantsDebiteurs = $stats['etudiantsDebiteurs'];
        $etudiantsCrediteurs = $stats['etudiantsCrediteurs'];

        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        return [
            'soldeCaisse' => $soldeCaisse,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'nombreEtudiants' => $nombreEtudiants,
            'nombreDebiteurs' => $nombreDebiteurs,
            'nombreCrediteurs' => $nombreCrediteurs,
            'totalDettes' => $stats['totalDettes'],
            'totalAvances' => $stats['totalAvances'],
            'maisons' => $maisons,
            'etudiantsDebiteurs' => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
        ];
    }

    /**
     * Génère un rapport trimestriel
     */
    public function genererRapportTrimestriel(int $trimestre, int $annee): string
    {
        $data = $this->collecterDonneesTrimestrielles($trimestre, $annee);
        
        $pdf = Pdf::loadView('admin.rapports.trimestriel', $data);
        
        $nomFichier = sprintf('rapport_trimestriel_%04d_T%d.pdf', $annee, $trimestre);
        $cheminComplet = 'reports/' . $nomFichier;
        
        Storage::put($cheminComplet, $pdf->output());
        
        return $cheminComplet;
    }

    /**
     * Collecte les données pour un trimestre
     */
    public function collecterDonneesTrimestrielles(int $trimestre, int $annee): array
    {
        // Calcul des mois du trimestre
        $moisDebut = ($trimestre - 1) * 3 + 1;
        $moisFin = $trimestre * 3;
        
        $dateDebut = Carbon::create($annee, $moisDebut, 1)->startOfMonth();
        $dateFin = Carbon::create($annee, $moisFin, 1)->endOfMonth();

        // Paiements du trimestre
        // Pour le trimestre, utiliser agrégations pour totaux et limiter les listes récupérées
        $totalRecettes = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Factures du trimestre
        $totalDepenses = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Solde
        $soldeCaisse = $totalRecettes - $totalDepenses;

        // Étudiants (listes limitées via streaming)
        $stats = $this->computeEtudiantsStats(1000, 1000);
        $etudiantsDebiteurs = $stats['etudiantsDebiteurs'];
        $etudiantsCrediteurs = $stats['etudiantsCrediteurs'];

        // Maisons (chargement léger)
        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        $nomsMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                     'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        
        $periodeDebut = $nomsMois[$moisDebut - 1];
        $periodeFin = $nomsMois[$moisFin - 1];

        return [
            'trimestre' => $trimestre,
            'annee' => $annee,
            'periode' => "$periodeDebut - $periodeFin $annee",
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            // pour listes détaillées (PDF) on charge un ensemble raisonnable de colonnes
            'paiements' => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                            ->with('etudiant')
                            ->orderBy('date_paiement')
                            ->get(['id','etudiant_id','montant','date_paiement']),
            'factures' => Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
                            ->with('maison')
                            ->orderBy('date_paiement')
                            ->get(['id','maison_id','montant','date_paiement','type']),
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'soldeCaisse' => $soldeCaisse,
            'etudiantsDebiteurs' => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
            'nombreEtudiants' => $stats['nombreEtudiants'],
            'nombreDebiteurs' => $stats['nombreDebiteurs'],
            'nombreCrediteurs' => $stats['nombreCrediteurs'],
            'totalDettes' => $stats['totalDettes'],
            'totalAvances' => $stats['totalAvances'],
            'maisons' => $maisons,
        ];
    }

    /**
     * Génère un rapport annuel (mandat)
     */
    public function genererRapportAnnuel(int $annee): string
    {
        $data = $this->collecterDonneesAnnuelles($annee);
        
        $pdf = Pdf::loadView('admin.rapports.annuel', $data);
        
        $nomFichier = sprintf('rapport_annuel_%04d.pdf', $annee);
        $cheminComplet = 'reports/' . $nomFichier;
        
        Storage::put($cheminComplet, $pdf->output());
        
        return $cheminComplet;
    }

    /**
     * Collecte les données pour une année complète
     */
    public function collecterDonneesAnnuelles(int $annee): array
    {
        $dateDebut = Carbon::create($annee, 1, 1)->startOfYear();
        $dateFin = Carbon::create($annee, 12, 31)->endOfYear();

        // Paiements de l'année
        // Année complète: utiliser agrégations pour totaux; générer statistiques mensuelles par somme
        $totalRecettes = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Factures de l'année
        $totalDepenses = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Solde
        $soldeCaisse = $totalRecettes - $totalDepenses;

        // Statistiques mensuelles
        $statistiquesMensuelles = [];
        for ($mois = 1; $mois <= 12; $mois++) {
            $debutMois = Carbon::create($annee, $mois, 1)->startOfMonth();
            $finMois = Carbon::create($annee, $mois, 1)->endOfMonth();
            
            $recettesMois = Paiement::whereBetween('date_paiement', [$debutMois, $finMois])->sum('montant');
            $depensesMois = Facture::whereBetween('date_paiement', [$debutMois, $finMois])->sum('montant');
            
            $statistiquesMensuelles[] = [
                'mois' => $mois,
                'nomMois' => $debutMois->locale('fr')->isoFormat('MMMM'),
                'recettes' => $recettesMois,
                'depenses' => $depensesMois,
                'solde' => $recettesMois - $depensesMois,
            ];
        }

        // Étudiants (listes limitées via streaming)
        $stats = $this->computeEtudiantsStats(1000, 1000);
        $etudiantsDebiteurs = $stats['etudiantsDebiteurs'];
        $etudiantsCrediteurs = $stats['etudiantsCrediteurs'];

        // Maisons (chargement léger)
        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        return [
            'annee' => $annee,
            'periode' => "Année $annee",
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            // listes détaillées
            'paiements' => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                            ->with('etudiant')
                            ->orderBy('date_paiement')
                            ->get(['id','etudiant_id','montant','date_paiement']),
            'factures' => Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
                            ->with('maison')
                            ->orderBy('date_paiement')
                            ->get(['id','maison_id','montant','date_paiement','type']),
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'soldeCaisse' => $soldeCaisse,
            'statistiquesMensuelles' => $statistiquesMensuelles,
            'etudiantsDebiteurs' => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
            'nombreEtudiants' => $stats['nombreEtudiants'],
            'nombreDebiteurs' => $stats['nombreDebiteurs'],
            'nombreCrediteurs' => $stats['nombreCrediteurs'],
            'totalDettes' => $stats['totalDettes'],
            'totalAvances' => $stats['totalAvances'],
            'maisons' => $maisons,
        ];
    }

    /**
     * Calcule des statistiques d'étudiants en streaming (utile car `solde` est un accessor)
     * Retourne counts, totaux et listes limitées de débiteurs/créditeurs.
     */
    private function computeEtudiantsStats(int $limitDeb = 1000, int $limitCred = 1000, int $limitForLists = null): array
    {
        $nombreEtudiants = 0;
        $nombreDebiteurs = 0;
        $nombreCrediteurs = 0;
        $totalDettes = 0;
        $totalAvances = 0;
        $etudiantsDebiteurs = collect();
        $etudiantsCrediteurs = collect();

        if (is_null($limitForLists)) {
            $limitForLists = max($limitDeb, $limitCred);
        }

        foreach (Etudiant::with('maison')->cursor() as $e) {
            $nombreEtudiants++;
            $s = $e->solde;
            if ($s < 0) {
                $nombreDebiteurs++;
                $totalDettes += $s; // négatif
                if ($etudiantsDebiteurs->count() < $limitDeb) {
                    $etudiantsDebiteurs->push($e);
                }
            } elseif ($s > 0) {
                $nombreCrediteurs++;
                $totalAvances += $s;
                if ($etudiantsCrediteurs->count() < $limitCred) {
                    $etudiantsCrediteurs->push($e);
                }
            }
            // arrêter si les deux listes ont atteint la taille demandée et qu'on ne veut pas compter plus ?
        }

        return [
            'nombreEtudiants' => $nombreEtudiants,
            'nombreDebiteurs' => $nombreDebiteurs,
            'nombreCrediteurs' => $nombreCrediteurs,
            'totalDettes' => abs($totalDettes),
            'totalAvances' => $totalAvances,
            'etudiantsDebiteurs' => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
        ];
    }
}