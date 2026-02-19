<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DemandePaiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementWaveController extends Controller
{
    // Afficher la page paiement Wave
    public function index()
    {
        $etudiant  = Auth::user()->etudiant;
        $montant   = $etudiant->loyer_mensuel;
        $waveLink  = config('services.wave.payment_link') . '?amount=' . (int) $montant;

        // Dernières demandes de l'étudiant
        $demandes = DemandePaiement::where('etudiant_id', $etudiant->id)
                                   ->latest()
                                   ->take(5)
                                   ->get();

        return view('client.paiements.wave', compact('etudiant', 'montant', 'waveLink', 'demandes'));
    }

    // Soumettre le numéro de transaction après paiement Wave
    public function soumettre(Request $request)
    {
        $request->validate([
            'transaction_id' => ['required', 'string', 'min:5', 'unique:demandes_paiement,transaction_id'],
        ], [
            'transaction_id.required' => 'Le numéro de transaction est obligatoire.',
            'transaction_id.unique'   => 'Ce numéro de transaction a déjà été soumis.',
            'transaction_id.min'      => 'Le numéro de transaction semble invalide.',
        ]);

        $etudiant = Auth::user()->etudiant;

        DemandePaiement::create([
            'etudiant_id'    => $etudiant->id,
            'montant'        => $etudiant->loyer_mensuel,
            'transaction_id' => strtoupper(trim($request->transaction_id)),
            'statut'         => 'soumis',
        ]);

        // Notification pour l'admin
        cache()->put('notif_admin_paiement', [
            'etudiant' => $etudiant->nom,
            'montant'  => $etudiant->loyer_mensuel,
            'transaction_id' => strtoupper(trim($request->transaction_id)),
            'date'     => now()->format('d/m/Y à H:i'),
        ], now()->addDays(30));

        return back()->with('success', '✅ Numéro de transaction soumis ! L\'admin va vérifier et valider votre paiement.');
    }
}