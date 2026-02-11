<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Facture;
use App\Models\Maison;
use Illuminate\Http\Request;

class FactureController extends Controller
{
   public function index()
{
    $factures = Facture::with('maison')
        ->orderBy('date_paiement', 'desc')
        ->paginate(20);

    // Statistiques globales
    $totalFactures = Facture::count();

    $facturesImpayees = Facture::where('statut', 'impayee')->count();

    // 💰 Montant total des factures impayées
    $montantDu = Facture::where('statut', 'impayee')->sum('montant');

    // ✅ Factures payées ce mois
    $facturesPayeesMois = Facture::where('statut', 'payee')
        ->whereMonth('date_paiement', now()->month)
        ->whereYear('date_paiement', now()->year)
        ->count();

    $montantTotal = Facture::sum('montant');

    return view('factures.index', compact(
        'factures',
        'totalFactures',
        'facturesImpayees',
        'montantDu',
        'facturesPayeesMois',
        'montantTotal'
    ));
}


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

    return view('factures.create', [
        'maisons'   => $maisons,
        'etudiants' => $etudiants,
        'types'     => $types,
    ]);
}



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

        return redirect()->route('factures.index')
            ->with('success', 'Facture enregistrée avec succès.');
    }

    public function edit(Facture $facture)
    {
        $maisons = Maison::all();
        $types = Facture::getTypes();
        
        return view('factures.edit', compact('facture', 'maisons', 'types'));
    }

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

        return redirect()->route('factures.index')
            ->with('success', 'Facture modifiée avec succès.');
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();

        return redirect()->route('factures.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    public function generatePdf(Facture $facture)
{
    // Exemple simple avec barryvdh/laravel-dompdf
    $pdf = \PDF::loadView('factures.pdf', compact('facture'));
    return $pdf->download('facture-' . $facture->id . '.pdf');
}

}