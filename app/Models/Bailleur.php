<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bailleur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'telephone',
    ];

    protected $appends = ['total_paye', 'total_du', 'solde'];

    /**
     * Relation : Un bailleur possède plusieurs maisons
     */
    public function maisons(): HasMany
    {
        return $this->hasMany(Maison::class);
    }

    /**
     * Relation : Un bailleur reçoit plusieurs paiements
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementBailleur::class);
    }

    /**
     * Scope : Bailleurs à qui on doit de l'argent
     */
    public function scopeEndettes($query)
    {
        return $query->whereRaw('(
            SELECT COALESCE(SUM(montant), 0) FROM paiement_bailleurs WHERE bailleur_id = bailleurs.id
        ) < (
            SELECT COALESCE(SUM(loyer_total_mensuel), 0) FROM maisons WHERE bailleur_id = bailleurs.id
        )');
    }

    /**
     * Calcule le total des paiements reçus par le bailleur
     */
    public function getTotalPayeAttribute(): float
    {
        return (float) $this->paiements()->sum('montant');
    }

    /**
     * Calcule le montant total dû au bailleur (somme des loyers de ses maisons par le nombre de mois)
     * Chaque maison accumule son loyer mensuel selon le nombre de mois depuis sa création
     */
    public function getTotalDuAttribute(): float
    {
        return $this->maisons->sum(function ($maison) {
            $moisDepuisCreation = \Carbon\Carbon::parse($maison->created_at)->diffInMonths(\Carbon\Carbon::now()) + 1;
            return (float) ($maison->loyer_total_mensuel * $moisDepuisCreation);
        });
    }

    /**
     * Calcule le solde restant (négatif = on doit au bailleur)
     */
    public function getSoldeAttribute(): float
    {
        return round($this->total_paye - $this->total_du, 2);
    }

    /**
     * Indique si on doit de l'argent au bailleur
     */
    public function isEndetted(): bool
    {
        return $this->solde < 0;
    }
}