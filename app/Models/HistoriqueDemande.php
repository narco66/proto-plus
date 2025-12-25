<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueDemande extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'demande_id',
        'action',
        'auteur_id',
        'commentaire',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }
}
