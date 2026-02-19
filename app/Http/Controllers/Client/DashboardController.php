<?php
// app/Http/Controllers/Client/DashboardController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $etudiant = $user->etudiant;

        // Sécurité : si le user n'a pas de profil étudiant lié
        if (!$etudiant) {
            Auth::logout();
            return redirect()->route('client.login')
                ->withErrors(['email' => 'Aucun profil étudiant associé à ce compte.']);
        }
          
         $notif = cache()->get('notif_paiement_' . Auth::id());
        if ($notif) {
            cache()->forget('notif_paiement_' . Auth::id()); // effacer après affichage
        }


        $paiements = $etudiant->paiements()
                              ->orderBy('date_paiement', 'desc')
                              ->get();

        return view('client.dashboard.index', [
            'etudiant'   => $etudiant,
            'paiements'  => $paiements,
            'totalPaye'  => $etudiant->total_paye,
            'totalDu'    => $etudiant->total_du,
            'solde'      => $etudiant->solde,
            'mois'       => $etudiant->mois,
            'notif'     => $notif,
        ]);
    }
}