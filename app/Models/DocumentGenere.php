<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentGenere extends Model
{
    use HasFactory;

    protected $table = 'documents_generes';

    protected $fillable = [
        'demande_id',
        'type_modele',
        'numero',
        'fichier_path',
        'signe',
        'date_generation',
        'generated_by',
    ];

    protected function casts(): array
    {
        return [
            'signe' => 'boolean',
            'date_generation' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
