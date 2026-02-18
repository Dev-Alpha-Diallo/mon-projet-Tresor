<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Etudiant extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nom',
        'filiere',
        'maison_id',
        'chambre',
        'loyer_mensuel',
        'date_debut',
    ];

    protected $casts = [
        'loyer_mensuel' => 'decimal:2',
        'date_debut' => 'date',
    ];

    /**
     * Relation : Un étudiant habite dans une maison
     */
    public function maison(): BelongsTo
    {
        return $this->belongsTo(Maison::class);
    }

    /**
     * Relation : Un étudiant effectue plusieurs paiements
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Calcule le total des paiements effectués par l'étudiant
     */
    public function getTotalPayeAttribute(): float
    {
        return $this->paiements()->sum('montant');
    }

    /**
     * Calcule le total des loyers dus depuis la date de début
     * Utilise date_debut si défini, sinon created_at
     */
    public function getTotalDuAttribute(): float
    {
        $dateReference = $this->date_debut ?? $this->created_at;
        $moisDepuisDebut = Carbon::parse($dateReference)->diffInMonths(Carbon::now()) + 1;
        return $this->loyer_mensuel * $moisDepuisDebut;
    }

    /**
     * Calcule le solde de l'étudiant
     * Solde positif = créditeur (avance)
     * Solde négatif = débiteur (dette)
     */
    public function getSoldeAttribute(): float
    {
        return $this->total_paye - $this->total_du;
    }

    /**
     * Vérifie si l'étudiant est débiteur
     */
    public function isDebiteur(): bool
    {
        return $this->solde < 0;
    }

    /**
     * Vérifie si l'étudiant est créditeur
     */
    public function isCrediteur(): bool
    {
        return $this->solde > 0;
    }

    public function parents()
    {
        return $this->hasMany(ParentTuteur::class);
    }

}
