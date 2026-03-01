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

    // ===== RELATIONS =====

    public function bailleur(): BelongsTo
    {
        return $this->belongsTo(Bailleur::class);
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function paiementsBailleur(): HasMany
    {
        return $this->hasMany(PaiementBailleur::class);
    }

    public function paiements(): HasManyThrough
    {
        return $this->hasManyThrough(Paiement::class, Etudiant::class);
    }

    // ===== ACCESSORS OPTIMISÉS =====

    /**
     * ✅ Utilise hasManyThrough au lieu de boucler sur les étudiants
     * Avant : 1 requête par étudiant → N requêtes
     * Après : 1 seule requête SQL
     */
    public function getTotalRecettesAttribute(): float
    {
        if ($this->relationLoaded('paiements')) {
            return (float) $this->paiements->sum('montant');
        }
        return (float) $this->paiements()->sum('montant');
    }

    /**
     * ✅ Utilise la relation chargée si disponible
     */
    public function getTotalDepensesAttribute(): float
    {
        if ($this->relationLoaded('factures')) {
            return (float) $this->factures->sum('montant');
        }
        return (float) $this->factures()->sum('montant');
    }

    /**
     * ✅ Utilise la relation chargée si disponible
     */
    public function getTotalPayeBailleurAttribute(): float
    {
        if ($this->relationLoaded('paiementsBailleur')) {
            return (float) $this->paiementsBailleur->sum('montant');
        }
        return (float) $this->paiementsBailleur()->sum('montant');
    }

    /**
     * ✅ Calcul pur — 0 requête supplémentaire
     */
    public function getSoldeAttribute(): float
    {
        return round(
            $this->total_recettes - $this->total_depenses - $this->total_paye_bailleur,
            2
        );
    }

    public function isRentable(): bool
    {
        return $this->solde > 0;
    }
}