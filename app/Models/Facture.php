<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'maison_id',
        'numero_facture',
        'type',
        'montant',
        'date_emission',
        'date_echeance',
        'date_paiement',
        'statut',
        'description',
        'remarques',
    ];

    protected $casts = [
        'date_emission'  => 'date',
        'date_echeance'  => 'date',
        'date_paiement'  => 'date',
        'montant'        => 'decimal:2',
    ];

    public function maison()
    {
        return $this->belongsTo(Maison::class);
    }

    public static function getTypes(): array
    {
        return [
            'eau'         => '💧 Eau',
            'electricite' => '⚡ Électricité',
            'reparation'  => '🛠 Réparation',
            'autre'       => '📦 Autre',
        ];
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'payee'   => 'Payée',
            'partiel' => 'Partielle',
            default   => 'Impayée',
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'payee'   => 'green',
            'partiel' => 'yellow',
            default   => 'red',
        };
    }

    // Est-elle en retard ?
    public function getIsEnRetardAttribute(): bool
    {
        return $this->statut !== 'payee' && $this->date_echeance->isPast();
    }
}