<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

   protected $fillable = [
    'maison_id',
    'type',
    'numero_facture',
    'montant',
    'date_paiement',
    'description',
    'statut'
];


    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    /**
     * Relation : Une facture concerne une maison
     */
    public function maison(): BelongsTo
    {
        return $this->belongsTo(Maison::class);
    }

    /**
     * Les types de factures disponibles
     */
    public static function getTypes(): array
    {
        return [
            'eau' => 'Eau',
            'electricite' => 'Électricité',
            'reparation' => 'Réparation',
            'autre' => 'Autre',
        ];
    }
}