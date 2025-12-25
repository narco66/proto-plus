<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AyantDroit extends Model
{
    use HasFactory;

    protected $fillable = [
        'fonctionnaire_user_id',
        'civilite',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'lien_familial',
        'nationalite',
        'passeport_num',
        'passeport_expire_at',
        'photo',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'passeport_expire_at' => 'date',
        ];
    }

    /**
     * Relations
     */
    public function fonctionnaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fonctionnaire_user_id');
    }

    /**
     * Scopes
     */
    public function scopeActifs($query)
    {
        return $query->where('status', 'actif');
    }

    /**
     * Accessors
     */
    public function getFullNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }
}
