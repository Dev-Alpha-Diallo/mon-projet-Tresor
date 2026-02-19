<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function show(Paiement $paiement)
    {
        // Sécurité : vérifier que ce paiement appartient bien à l'étudiant connecté
        if ($paiement->etudiant_id !== Auth::user()->etudiant->id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('client.paiements.show', compact('paiement'));
    }
}