<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'libelle',
        'actif',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'version' => 'integer',
        ];
    }

    /**
     * Relations
     */
    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStepDefinition::class, 'workflow_definition_id')->orderBy('ordre');
    }
}
