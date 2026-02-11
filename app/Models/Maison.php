<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Maison extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'bailleur_id',
        'loyer_total_mensuel',
    ];

    protected $casts = [
        'loyer_total_mensuel' => 'decimal:2',
    ];

    /**
     * Relation : Une maison appartient à un bailleur
     */
    public function bailleur(): BelongsTo
    {
        return $this->belongsTo(Bailleur::class);
    }

    /**
     * Relation : Une maison héberge plusieurs étudiants
     */
    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    /**
     * Relation : Une maison a plusieurs factures
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    /**
     * Relation : Paiements effectués au bailleur pour cette maison
     */
    public function paiementsBailleur(): HasMany
    {
        return $this->hasMany(PaiementBailleur::class);
    }

    /**
     * Relation : Paiements des étudiants de cette maison (via la relation etudiants)
     * Note : Les paiements sont enregistrés au niveau de l'étudiant
     */
    public function paiements(): HasManyThrough
    {
        return $this->hasManyThrough(Paiement::class, Etudiant::class);
    }

    /**
     * Calcule le total des paiements reçus des étudiants de cette maison
     */
    public function getTotalRecettesAttribute(): float
    {
        return $this->etudiants->sum(function ($etudiant) {
            return $etudiant->paiements()->sum('montant');
        });
    }

    /**
     * Calcule le total des dépenses pour cette maison (factures)
     */
    public function getTotalDepensesAttribute(): float
    {
        return (float) $this->factures()->sum('montant');
    }

    /**
     * Calcule le total payé au bailleur pour cette maison
     */
    public function getTotalPayeBailleurAttribute(): float
    {
        try {
            return (float) $this->paiementsBailleur()->sum('montant');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcule le bilan net de la maison (recettes - dépenses - paiements bailleur)
     */
    public function getSoldeAttribute(): float
    {
        return round($this->total_recettes - $this->total_depenses - $this->total_paye_bailleur, 2);
    }

    /**
     * Déduit si la maison est rentable
     */
    public function isRentable(): bool
    {
        return $this->solde > 0;
    }
}