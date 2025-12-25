<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'type_demande',
        'demandeur_user_id',
        'statut',
        'motif',
        'date_depart_prevue',
        'pays_destination',
        'date_soumission',
        'date_validation',
        'date_rejet',
        'motif_rejet',
        'date_expiration',
        'priorite',
        'canal',
    ];

    protected function casts(): array
    {
        return [
            'date_depart_prevue' => 'date',
            'date_soumission' => 'datetime',
            'date_validation' => 'datetime',
            'date_rejet' => 'datetime',
            'date_expiration' => 'date',
        ];
    }

    /**
     * Relations
     */
    public function demandeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'demandeur_user_id');
    }

    public function beneficiaires(): HasMany
    {
        return $this->hasMany(DemandeBeneficiaire::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function documentsGeneres(): HasMany
    {
        return $this->hasMany(DocumentGenere::class);
    }

    public function workflowInstance(): HasOne
    {
        return $this->hasOne(WorkflowInstance::class);
    }

    public function historique(): HasMany
    {
        return $this->hasMany(HistoriqueDemande::class);
    }

    /**
     * Scopes
     */
    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeSoumis($query)
    {
        return $query->where('statut', 'soumis');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeUrgentes($query)
    {
        return $query->where('priorite', 'urgent');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_demande', $type);
    }

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($demande) {
            if (empty($demande->reference)) {
                $demande->reference = self::generateReference();
            }
        });
    }

    /**
     * Génération de référence unique
     */
    public static function generateReference(): string
    {
        $year = date('Y');
        $lastDemande = self::where('reference', 'like', "PROTO-{$year}-%")
            ->orderBy('reference', 'desc')
            ->first();

        if ($lastDemande) {
            $lastNumber = (int) substr($lastDemande->reference, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('PROTO-%s-%06d', $year, $newNumber);
    }
}
