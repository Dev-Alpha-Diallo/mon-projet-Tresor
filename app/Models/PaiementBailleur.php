<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementBailleur extends Model
{
    use HasFactory;

    /**
     * Nom explicite de la table (corrige la pluralisation inattendue)
     */
    protected $table = 'paiements_bailleurs';

    protected $fillable = [
        'bailleur_id',
        'maison_id',
        'montant',
        'date_paiement',
        'remarque',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    /**
     * Relation : Un paiement appartient à un bailleur
     */
    public function bailleur(): BelongsTo
    {
        return $this->belongsTo(Bailleur::class);
    }

    /**
     * Relation : Un paiement concerne une maison
     */
    public function maison(): BelongsTo
    {
        return $this->belongsTo(Maison::class);
    }
}