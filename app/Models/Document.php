<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'beneficiaire_type',
        'beneficiaire_id',
        'type_document',
        'nom_fichier',
        'chemin_fichier',
        'mime_type',
        'taille',
        'checksum',
        'confidentiel',
        'version',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'confidentiel' => 'boolean',
            'taille' => 'integer',
            'version' => 'integer',
        ];
    }

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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
