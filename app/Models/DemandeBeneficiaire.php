<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DemandeBeneficiaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'beneficiaire_type',
        'beneficiaire_id',
        'role_dans_demande',
        'commentaire',
    ];

    /**
     * Relations
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function beneficiaire()
    {
        if ($this->beneficiaire_type === 'fonctionnaire') {
            return $this->belongsTo(\App\Models\User::class, 'beneficiaire_id');
        } else {
            return $this->belongsTo(\App\Models\AyantDroit::class, 'beneficiaire_id');
        }
    }
}
