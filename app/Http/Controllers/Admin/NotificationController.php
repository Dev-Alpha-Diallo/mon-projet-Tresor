<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandePaiement;
use App\Models\Paiement;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $demandes = DemandePaiement::with('etudiant.maison')
                                ->whereHas('etudiant') // ← ignorer les orphelines
                                ->orderBy('created_at', 'desc')
                                ->get();

        $enAttente = $demandes->where('statut', 'soumis')->count();

        return view('admin.notifications.index', compact('demandes', 'enAttente'));
    }

    public function valider(Request $request, $id)
    {
        $demande = DemandePaiement::findOrFail($id);

        // Créer le paiement automatiquement
        Paiement::create([
            'etudiant_id'    => $demande->etudiant_id,
            'montant'        => $demande->montant,
            'date_paiement'  => now(),
            'moyen_paiement' => 'Wave',
            'remarque'       => 'Transaction Wave : ' . $demande->transaction_id,
        ]);

        $demande->update(['statut' => 'valide']);

        // Notifier l'étudiant
        cache()->put(
            'notif_paiement_' . $demande->etudiant->user_id,
            [
                'montant' => $demande->montant,
                'date'    => now()->format('d/m/Y'),
            ],
            now()->addDays(7)
        );

        return back()->with('success', '✅ Paiement validé et enregistré !');
    }

    public function rejeter(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:255',
        ]);

        DemandePaiement::findOrFail($id)->update([
            'statut' => 'rejete',
            'note'   => $request->note,
        ]);

        return back()->with('success', '❌ Demande rejetée.');
    }
}