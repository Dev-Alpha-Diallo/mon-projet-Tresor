<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Facture;
use App\Models\Maison;
use App\Models\Paiement;
use App\Models\PaiementBailleur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class RapportService
{
    // Durée du cache dashboard en minutes
    const CACHE_DASHBOARD_MINUTES = 5;
    const CACHE_DASHBOARD_KEY     = 'dashboard_admin';

    // ===== DASHBOARD =====

    public function getDonneesDashboard(): array
    {
        return Cache::remember(self::CACHE_DASHBOARD_KEY, now()->addMinutes(self::CACHE_DASHBOARD_MINUTES), function () {
            return $this->computeDashboard();
        });
    }

    /**
     * Vider le cache dashboard — à appeler après chaque paiement créé/modifié
     */
    public function clearDashboardCache(): void
    {
        Cache::forget(self::CACHE_DASHBOARD_KEY);
    }

    private function computeDashboard(): array
    {
        $totalRecettes = Paiement::sum('montant');
        $totalDepenses = Facture::sum('montant');
        $soldeCaisse   = $totalRecettes - $totalDepenses;

        $stats  = $this->computeEtudiantsStats(20, 20);
        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        return [
            'soldeCaisse'       => $soldeCaisse,
            'totalRecettes'     => $totalRecettes,
            'totalDepenses'     => $totalDepenses,
            'nombreEtudiants'   => $stats['nombreEtudiants'],
            'nombreDebiteurs'   => $stats['nombreDebiteurs'],
            'nombreCrediteurs'  => $stats['nombreCrediteurs'],
            'totalDettes'       => $stats['totalDettes'],
            'totalAvances'      => $stats['totalAvances'],
            'maisons'           => $maisons,
            'etudiantsDebiteurs'  => $stats['etudiantsDebiteurs'],
            'etudiantsCrediteurs' => $stats['etudiantsCrediteurs'],
        ];
    }

    // ===== STATS ÉTUDIANTS OPTIMISÉES =====

    /**
     * Calcul des stats en une seule requête SQL
     * au lieu de cursor() qui faisait N requêtes
     */
    private function computeEtudiantsStats(int $limitDeb = 1000, int $limitCred = 1000): array
    {
        // 1 seule requête : charge tous les étudiants + leurs paiements en mémoire
        $etudiants = Etudiant::with(['paiements', 'maison'])->get();

        $nombreEtudiants  = 0;
        $nombreDebiteurs  = 0;
        $nombreCrediteurs = 0;
        $totalDettes      = 0.0;
        $totalAvances     = 0.0;
        $etudiantsDebiteurs  = collect();
        $etudiantsCrediteurs = collect();

        foreach ($etudiants as $e) {
            $nombreEtudiants++;
            $solde = $e->solde; // utilise l'accessor optimisé → 0 requête SQL

            if ($solde < 0) {
                $nombreDebiteurs++;
                $totalDettes += $solde;
                if ($etudiantsDebiteurs->count() < $limitDeb) {
                    $etudiantsDebiteurs->push($e);
                }
            } elseif ($solde > 0) {
                $nombreCrediteurs++;
                $totalAvances += $solde;
                if ($etudiantsCrediteurs->count() < $limitCred) {
                    $etudiantsCrediteurs->push($e);
                }
            }
        }

        return [
            'nombreEtudiants'     => $nombreEtudiants,
            'nombreDebiteurs'     => $nombreDebiteurs,
            'nombreCrediteurs'    => $nombreCrediteurs,
            'totalDettes'         => abs($totalDettes),
            'totalAvances'        => $totalAvances,
            'etudiantsDebiteurs'  => $etudiantsDebiteurs,
            'etudiantsCrediteurs' => $etudiantsCrediteurs,
        ];
    }

    // ===== RAPPORT MENSUEL =====

    public function genererRapportMensuel(int $mois, int $annee): string
    {
        $data = $this->collecterDonneesMensuelles($mois, $annee);
        $pdf  = Pdf::loadView('admin.rapports.mensuel', $data);

        $nomFichier   = sprintf('rapport_tresorerie_%04d_%02d.pdf', $annee, $mois);
        $cheminComplet = 'reports/' . $nomFichier;
        Storage::put($cheminComplet, $pdf->output());

        return $cheminComplet;
    }

    public function collecterDonneesMensuelles(int $mois, int $annee): array
    {
        $dateDebut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $dateFin   = Carbon::create($annee, $mois, 1)->endOfMonth();

        $totalRecettes           = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');
        $totalDepenses           = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');
        $totalPaiementsBailleurs = PaiementBailleur::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        $depensesParType = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->selectRaw('type, SUM(montant) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $soldeDebut = $this->calculerSoldeJusquaDate($dateDebut->copy()->subDay());
        $soldeFin   = $soldeDebut + $totalRecettes - $totalDepenses - $totalPaiementsBailleurs;

        $stats  = $this->computeEtudiantsStats(1000, 1000);
        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        return [
            'mois'           => $mois,
            'annee'          => $annee,
            'moisNom'        => Carbon::create($annee, $mois, 1)->locale('fr')->monthName,
            'dateGeneration' => Carbon::now()->format('d/m/Y H:i'),
            'totalRecettes'  => $totalRecettes,
            'paiementsMois'  => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                    ->with('etudiant')
                                    ->orderBy('date_paiement')
                                    ->get(['id', 'etudiant_id', 'montant', 'date_paiement']),
            'totalDepenses'  => $totalDepenses,
            'facturesMois'   => Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                    ->with('maison')
                                    ->orderBy('date_paiement')
                                    ->get(['id', 'maison_id', 'montant', 'date_paiement', 'type']),
            'depensesParType'            => $depensesParType,
            'totalPaiementsBailleurs'    => $totalPaiementsBailleurs,
            'paiementsBailleursMois'     => PaiementBailleur::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                                ->with('maison')
                                                ->orderBy('date_paiement')
                                                ->get(['id', 'maison_id', 'montant', 'date_paiement']),
            'soldeDebut'       => $soldeDebut,
            'soldeFin'         => $soldeFin,
            'excedentDeficit'  => $soldeFin - $soldeDebut,
            'etudiantsDebiteurs'  => $stats['etudiantsDebiteurs'],
            'etudiantsCrediteurs' => $stats['etudiantsCrediteurs'],
            'nombreEtudiants'     => $stats['nombreEtudiants'],
            'nombreDebiteurs'     => $stats['nombreDebiteurs'],
            'nombreCrediteurs'    => $stats['nombreCrediteurs'],
            'totalDettes'         => abs($stats['totalDettes']),
            'totalAvances'        => $stats['totalAvances'],
            'maisons'             => $maisons,
        ];
    }

    // ===== RAPPORT TRIMESTRIEL =====

    public function genererRapportTrimestriel(int $trimestre, int $annee): string
    {
        $data = $this->collecterDonneesTrimestrielles($trimestre, $annee);
        $pdf  = Pdf::loadView('admin.rapports.trimestriel', $data);

        $nomFichier   = sprintf('rapport_trimestriel_%04d_T%d.pdf', $annee, $trimestre);
        $cheminComplet = 'reports/' . $nomFichier;
        Storage::put($cheminComplet, $pdf->output());

        return $cheminComplet;
    }

    public function collecterDonneesTrimestrielles(int $trimestre, int $annee): array
    {
        $moisDebut = ($trimestre - 1) * 3 + 1;
        $moisFin   = $trimestre * 3;
        $dateDebut = Carbon::create($annee, $moisDebut, 1)->startOfMonth();
        $dateFin   = Carbon::create($annee, $moisFin, 1)->endOfMonth();

        $totalRecettes = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');
        $totalDepenses = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        $stats  = $this->computeEtudiantsStats(1000, 1000);
        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        $nomsMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                     'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        return [
            'trimestre'    => $trimestre,
            'annee'        => $annee,
            'periode'      => "{$nomsMois[$moisDebut - 1]} - {$nomsMois[$moisFin - 1]} $annee",
            'dateDebut'    => $dateDebut,
            'dateFin'      => $dateFin,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'soldeCaisse'   => $totalRecettes - $totalDepenses,
            'paiements'    => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('etudiant')->orderBy('date_paiement')
                                ->get(['id', 'etudiant_id', 'montant', 'date_paiement']),
            'factures'     => Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('maison')->orderBy('date_paiement')
                                ->get(['id', 'maison_id', 'montant', 'date_paiement', 'type']),
            'etudiantsDebiteurs'  => $stats['etudiantsDebiteurs'],
            'etudiantsCrediteurs' => $stats['etudiantsCrediteurs'],
            'nombreEtudiants'     => $stats['nombreEtudiants'],
            'nombreDebiteurs'     => $stats['nombreDebiteurs'],
            'nombreCrediteurs'    => $stats['nombreCrediteurs'],
            'totalDettes'         => $stats['totalDettes'],
            'totalAvances'        => $stats['totalAvances'],
            'maisons'             => $maisons,
        ];
    }

    // ===== RAPPORT ANNUEL =====

    public function genererRapportAnnuel(int $annee): string
    {
        $data = $this->collecterDonneesAnnuelles($annee);
        $pdf  = Pdf::loadView('admin.rapports.annuel', $data);

        $nomFichier   = sprintf('rapport_annuel_%04d.pdf', $annee);
        $cheminComplet = 'reports/' . $nomFichier;
        Storage::put($cheminComplet, $pdf->output());

        return $cheminComplet;
    }

    public function collecterDonneesAnnuelles(int $annee): array
    {
        $dateDebut = Carbon::create($annee, 1, 1)->startOfYear();
        $dateFin   = Carbon::create($annee, 12, 31)->endOfYear();

        $totalRecettes = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');
        $totalDepenses = Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant');

        // Stats mensuelles en une seule passe
        $statistiquesMensuelles = [];
        for ($mois = 1; $mois <= 12; $mois++) {
            $debutMois = Carbon::create($annee, $mois, 1)->startOfMonth();
            $finMois   = Carbon::create($annee, $mois, 1)->endOfMonth();

            $recettesMois = Paiement::whereBetween('date_paiement', [$debutMois, $finMois])->sum('montant');
            $depensesMois = Facture::whereBetween('date_paiement', [$debutMois, $finMois])->sum('montant');

            $statistiquesMensuelles[] = [
                'mois'    => $mois,
                'nomMois' => $debutMois->locale('fr')->isoFormat('MMMM'),
                'recettes' => $recettesMois,
                'depenses' => $depensesMois,
                'solde'    => $recettesMois - $depensesMois,
            ];
        }

        $stats  = $this->computeEtudiantsStats(1000, 1000);
        $maisons = Maison::with('bailleur')->withCount('etudiants')->get();

        return [
            'annee'        => $annee,
            'periode'      => "Année $annee",
            'dateDebut'    => $dateDebut,
            'dateFin'      => $dateFin,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'soldeCaisse'   => $totalRecettes - $totalDepenses,
            'statistiquesMensuelles' => $statistiquesMensuelles,
            'paiements'    => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('etudiant')->orderBy('date_paiement')
                                ->get(['id', 'etudiant_id', 'montant', 'date_paiement']),
            'factures'     => Facture::whereBetween('date_paiement', [$dateDebut, $dateFin])
                                ->with('maison')->orderBy('date_paiement')
                                ->get(['id', 'maison_id', 'montant', 'date_paiement', 'type']),
            'etudiantsDebiteurs'  => $stats['etudiantsDebiteurs'],
            'etudiantsCrediteurs' => $stats['etudiantsCrediteurs'],
            'nombreEtudiants'     => $stats['nombreEtudiants'],
            'nombreDebiteurs'     => $stats['nombreDebiteurs'],
            'nombreCrediteurs'    => $stats['nombreCrediteurs'],
            'totalDettes'         => $stats['totalDettes'],
            'totalAvances'        => $stats['totalAvances'],
            'maisons'             => $maisons,
        ];
    }

    // ===== HELPERS =====

    private function calculerSoldeJusquaDate(Carbon $date): float
    {
        return (float) Paiement::where('date_paiement', '<=', $date)->sum('montant')
             - (float) Facture::where('date_paiement', '<=', $date)->sum('montant');
    }
}