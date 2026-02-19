<?php
// app/Models/DemandePaiement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandePaiement extends Model
{
    protected $table = 'demandes_paiement';

    protected $fillable = [
        'etudiant_id',
        'montant',
        'transaction_id',
        'statut',
        'note',
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function isEnAttente(): bool { return $this->statut === 'en_attente'; }
    public function isSoumis(): bool    { return $this->statut === 'soumis'; }
    public function isValide(): bool    { return $this->statut === 'valide'; }
    public function isRejete(): bool    { return $this->statut === 'rejete'; }
}