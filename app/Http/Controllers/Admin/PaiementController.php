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
            'montant'        => 'required|numeric|min:0.01|max:999999.99',
            'date_paiement'  => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:especes,mobile_money,virement',
            'remarque'       => 'nullable|string|max:500',
        ]);

        $etudiant = Etudiant::findOrFail($validated['etudiant_id']);

        // Validation date
        if ($validated['date_paiement'] < $etudiant->created_at->format('Y-m-d')) {
            return back()->withInput()->withErrors([
                'date_paiement' => 'La date de paiement ne peut pas être avant l\'inscription de l\'étudiant.',
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

        // ← Vider le cache dashboard
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
            'montant'        => 'required|numeric|min:0.01|max:999999.99',
            'date_paiement'  => 'required|date|before_or_equal:today',
            'moyen_paiement' => 'required|in:especes,mobile_money,virement',
            'remarque'       => 'nullable|string|max:500',
        ]);

        $paiement->update($validated);

        // ← Vider le cache dashboard
        $this->rapportService->clearDashboardCache();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement modifié avec succès.');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();

        // ← Vider le cache dashboard
        $this->rapportService->clearDashboardCache();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}