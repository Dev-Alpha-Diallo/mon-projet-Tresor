<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Maison;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::with('maison')->orderBy('date_echeance', 'desc');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('maison_id')) {
            $query->where('maison_id', $request->maison_id);
        }

        $factures = $query->paginate(20)->withQueryString();

        // 📊 Statistiques
        $totalFactures    = Facture::count();
        $facturesImpayees = Facture::where('statut', 'impayee')->count();
        $montantDu        = Facture::whereIn('statut', ['impayee', 'partiel'])->sum('montant');
        $montantTotal     = Facture::sum('montant');

        // ✅ Factures payées ce mois
        $facturesPayeesMois = Facture::where('statut', 'payee')
            ->whereMonth('date_paiement', Carbon::now()->month)
            ->whereYear('date_paiement', Carbon::now()->year)
            ->count();

        $maisons = Maison::orderBy('nom')->get();

        return view('admin.factures.index', compact(
            'factures',
            'totalFactures',
            'facturesImpayees',
            'montantDu',
            'montantTotal',
            'facturesPayeesMois', // ✅ AJOUT IMPORTANT
            'maisons'
        ));
    }

    public function create()
    {
        $maisons = Maison::orderBy('nom')->get();
        $types   = Facture::getTypes();

        return view('admin.factures.create', compact('maisons', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'maison_id'       => 'required|exists:maisons,id',
            'numero_facture'  => 'required|string|unique:factures,numero_facture',
            'type'            => 'required|in:eau,electricite,reparation,autre',
            'montant'         => 'required|numeric|min:0',
            'date_emission'   => 'required|date',
            'date_echeance'   => 'required|date|after_or_equal:date_emission',
            'date_paiement'   => 'nullable|date',
            'statut'          => 'required|in:impayee,partiel,payee',
            'description'     => 'nullable|string|max:1000',
            'remarques'       => 'nullable|string|max:1000',
        ]);

        if ($validated['statut'] === 'payee' && empty($validated['date_paiement'])) {
            $validated['date_paiement'] = now()->toDateString();
        }

        if ($validated['statut'] === 'impayee') {
            $validated['date_paiement'] = null;
        }

        Facture::create($validated);

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture créée avec succès.');
    }

    public function show(Facture $facture)
    {
        $facture->load('maison');
        return view('admin.factures.show', compact('facture'));
    }

    public function edit(Facture $facture)
    {
        $maisons = Maison::orderBy('nom')->get();
        $types   = Facture::getTypes();

        return view('admin.factures.edit', compact('facture', 'maisons', 'types'));
    }

    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'maison_id'      => 'required|exists:maisons,id',
            'numero_facture' => 'required|string|unique:factures,numero_facture,' . $facture->id,
            'type'           => 'required|in:eau,electricite,reparation,autre',
            'montant'        => 'required|numeric|min:0',
            'date_emission'  => 'required|date',
            'date_echeance'  => 'required|date|after_or_equal:date_emission',
            'date_paiement'  => 'nullable|date',
            'statut'         => 'required|in:impayee,partiel,payee',
            'description'    => 'nullable|string|max:1000',
            'remarques'      => 'nullable|string|max:1000',
        ]);

        if ($validated['statut'] === 'payee' && empty($validated['date_paiement'])) {
            $validated['date_paiement'] = now()->toDateString();
        }

        if ($validated['statut'] === 'impayee') {
            $validated['date_paiement'] = null;
        }

        $facture->update($validated);

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture modifiée avec succès.');
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    public function generatePdf(Facture $facture)
    {
        $facture->load('maison');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.factures.pdf', compact('facture'));

        return $pdf->download(
            'facture-' . str_pad($facture->id, 6, '0', STR_PAD_LEFT) . '.pdf'
        );
    }
}