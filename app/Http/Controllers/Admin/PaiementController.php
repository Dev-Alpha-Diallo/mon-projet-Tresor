<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Paiement;
use App\Models\Etudiant;
use App\Services\RapportService;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function __construct(private RapportService $rapportService) {}

    public function index(Request $request)
    {
        $query = Paiement::query()->with('etudiant.maison');

        if ($request->filled('nom')) {
            $query->whereHas('etudiant', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->nom . '%');
            });
        }

        if ($request->filled('maison_id')) {
            $query->whereHas('etudiant', function ($q) use ($request) {
                $q->where('maison_id', $request->maison_id);
            });
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(20);
        $maisons   = Maison::all();

        return view('admin.paiements.index', compact('paiements', 'maisons'));
    }

    public function create()
    {
        $selected = null;
        if (old('etudiant_id')) {
            $selected = Etudiant::with('maison')->find(old('etudiant_id'));
        }
        return view('admin.paiements.create', compact('selected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id'    => 'required|exists:etudiants,id',
            'mois_paiement'  => 'required|date', // ✅ ajouté
            'montant'        => 'required|numeric|min:0.01|max:999999.99',
            'date_paiement'  => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:especes,mobile_money,virement',
            'remarque'       => 'nullable|string|max:500',
        ]);

        $etudiant = Etudiant::findOrFail($validated['etudiant_id']);

        // Validation date paiement
        if ($validated['date_paiement'] < $etudiant->created_at->format('Y-m-d')) {
            return back()->withInput()->withErrors([
                'date_paiement' => 'La date ne peut pas être avant l\'inscription de l\'étudiant.',
            ]);
        }

        // ✅ Vérifier si ce mois est déjà payé pour cet étudiant
        $dejaPayé = Paiement::where('etudiant_id', $validated['etudiant_id'])
            ->whereYear('mois_paiement',  date('Y', strtotime($validated['mois_paiement'])))
            ->whereMonth('mois_paiement', date('m', strtotime($validated['mois_paiement'])))
            ->exists();

        if ($dejaPayé) {
            return back()->withInput()->withErrors([
                'mois_paiement' => 'Cet étudiant a déjà un paiement enregistré pour ce mois.',
            ]);
        }

        Paiement::create($validated);

        // Notification étudiant
        if ($etudiant->user_id) {
            cache()->put(
                'notif_paiement_' . $etudiant->user_id,
                ['montant' => $request->montant, 'date' => now()->format('d/m/Y')],
                now()->addDays(7)
            );
        }

        $this->rapportService->clearDashboardCache();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function edit(Paiement $paiement)
    {
        $selected = $paiement->etudiant()->with('maison')->first();
        return view('admin.paiements.edit', compact('paiement', 'selected'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([
            'etudiant_id'    => 'required|exists:etudiants,id',
            'mois_paiement'  => 'required|date', // ✅ ajouté
            'montant'        => 'required|numeric|min:0.01|max:999999.99',
            'date_paiement'  => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:especes,mobile_money,virement',
            'remarque'       => 'nullable|string|max:500',
        ]);

        // ✅ Vérifier doublon sauf pour le paiement en cours de modification
        $dejaPayé = Paiement::where('etudiant_id', $validated['etudiant_id'])
            ->whereYear('mois_paiement',  date('Y', strtotime($validated['mois_paiement'])))
            ->whereMonth('mois_paiement', date('m', strtotime($validated['mois_paiement'])))
            ->where('id', '!=', $paiement->id) // ✅ exclure le paiement actuel
            ->exists();

        if ($dejaPayé) {
            return back()->withInput()->withErrors([
                'mois_paiement' => 'Cet étudiant a déjà un paiement enregistré pour ce mois.',
            ]);
        }

        $paiement->update($validated);

        $this->rapportService->clearDashboardCache();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement modifié avec succès.');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        $this->rapportService->clearDashboardCache();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}