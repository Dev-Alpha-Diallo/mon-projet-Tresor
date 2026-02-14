<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Facture;
use App\Models\Maison;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    /**
     * Afficher la liste des factures avec filtres facultatifs par mois/année
     */
    public function index(Request $request)
    {
        // Validation facultative des filtres
        $validated = $request->validate([
            'mois'  => 'nullable|integer|min:1|max:12',
            'annee' => 'nullable|integer|min:2000|max:2100',
        ]);

        // Requête de base
        $query = Facture::with('maison')->orderBy('date_paiement', 'desc');

        // Appliquer filtre si fourni
        if (!empty($validated['mois']) && !empty($validated['annee'])) {
            $query->whereMonth('date_paiement', $validated['mois'])
                  ->whereYear('date_paiement', $validated['annee']);
        }

        // Pagination
        $factures = $query->paginate(20);

        // Statistiques globales
        $totalFactures = Facture::count();
        $facturesImpayees = Facture::where('statut', 'impayee')->count();
        $montantDu = Facture::where('statut', 'impayee')->sum('montant');
        $facturesPayeesMois = Facture::where('statut', 'payee')
            ->whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->count();
        $montantTotal = Facture::sum('montant');

        return view('admin.factures.index', compact(
            'factures',
            'totalFactures',
            'facturesImpayees',
            'montantDu',
            'facturesPayeesMois',
            'montantTotal'
        ));
    }

    /**
     * Afficher le formulaire de création d'une facture
     */
    public function create()
    {
        $maisons = Maison::orderBy('nom')->get();
        $etudiants = Etudiant::orderBy('nom')->get();
        $types = [
            'eau' => 'Eau',
            'electricite' => 'Électricité',
            'réparation' => 'Réparation',
            'autre' => 'Autre',
        ];

        return view('admin.factures.create', compact('maisons', 'etudiants', 'types'));
    }

    /**
     * Enregistrer une nouvelle facture
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_facture' => 'required|string|unique:factures,numero_facture',
            'type' => 'required|in:eau,electricite,reparation,autre',
            'maison_id' => 'required|exists:maisons,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Facture::create($validated);

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture enregistrée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition d'une facture
     */
    public function edit(Facture $facture)
    {
        $maisons = Maison::all();
        $types = Facture::getTypes();

        return view('admin.factures.edit', compact('facture', 'maisons', 'types'));
    }

    /**
     * Mettre à jour une facture existante
     */
    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'numero_facture' => 'required|string|unique:factures,numero_facture,' . $facture->id,
            'type' => 'required|in:eau,electricite,reparation,autre',
            'maison_id' => 'required|exists:maisons,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $facture->update($validated);

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture modifiée avec succès.');
    }

    /**
     * Supprimer une facture
     */
    public function destroy(Facture $facture)
    {
        $facture->delete();

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    /**
     * Générer un PDF pour une facture
     */
    public function generatePdf(Facture $facture)
    {
        // Exemple simple avec barryvdh/laravel-dompdf
        $pdf = \PDF::loadview('admin.factures.pdf', compact('facture'));
        return $pdf->download('facture-' . $facture->id . '.pdf');
    }
}
