<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'montant',
        'date_paiement',
        'moyen_paiement',
        'remarque',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    /**
     * Relation : Un paiement appartient à un étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    /**
     * Récupère la maison via l'étudiant
     */
    public function getMaison()
    {
        return $this->etudiant->maison;
    }
}