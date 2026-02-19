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
        'nom', 'telephone', 'email', 'filiere',
        'maison_id', 'chambre', 'loyer_mensuel',
        'date_debut', 'user_id',
    ];

    protected $casts = [
        'loyer_mensuel' => 'decimal:2',
        'date_debut'    => 'date',
    ];

    // ===== RELATIONS =====

    public function maison(): BelongsTo
    {
        return $this->belongsTo(Maison::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentTuteur::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== ACCESSORS OPTIMISÉS =====

    /**
     * Utilise la relation déjà chargée si disponible
     * → 0 requête si with('paiements') utilisé
     * → 1 requête sinon
     */
    public function getTotalPayeAttribute(): float
    {
        if ($this->relationLoaded('paiements')) {
            return (float) $this->paiements->sum('montant');
        }
        return (float) $this->paiements()->sum('montant');
    }

    /**
     * Calcul pur — aucune requête SQL
     */
    public function getTotalDuAttribute(): float
    {
        $dateReference   = $this->date_debut ?? $this->created_at;
        $moisDepuisDebut = Carbon::parse($dateReference)
                                 ->startOfMonth()
                                 ->diffInMonths(Carbon::now()->startOfMonth()) + 1;
        return (float) ($this->loyer_mensuel * $moisDepuisDebut);
    }

    /**
     * Calcul pur — aucune requête SQL
     */
    public function getSoldeAttribute(): float
    {
        return $this->total_paye - $this->total_du;
    }

    // ===== HELPERS =====

    public function isDebiteur(): bool
    {
        return $this->solde < 0;
    }

    public function isCrediteur(): bool
    {
        return $this->solde > 0;
    }

    /**
     * Suivi mensuel — utilise la relation déjà chargée
     * → 0 requête si with('paiements') utilisé
     */
    public function getMoisAttribute(): array
    {
        $dateDebut  = Carbon::parse($this->date_debut)->startOfMonth();
        $maintenant = Carbon::now()->startOfMonth();
        $mois       = [];

        // Utilise les paiements déjà en mémoire si disponibles
        $paiements = $this->relationLoaded('paiements')
            ? $this->paiements
            : $this->paiements()->get();

        $paiementsParMois = $paiements->groupBy(
            fn($p) => Carbon::parse($p->date_paiement)->format('Y-m')
        );

        $current = $dateDebut->copy();
        while ($current->lte($maintenant)) {
            $key    = $current->format('Y-m');
            $mois[] = [
                'mois'    => $current->copy(),
                'label'   => ucfirst($current->translatedFormat('F Y')),
                'paye'    => isset($paiementsParMois[$key]),
                'montant' => isset($paiementsParMois[$key])
                              ? $paiementsParMois[$key]->sum('montant')
                              : $this->loyer_mensuel,
            ];
            $current->addMonth();
        }

        return array_reverse($mois);
    }
}