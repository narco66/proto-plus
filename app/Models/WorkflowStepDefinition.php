<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStepDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_definition_id',
        'ordre',
        'libelle',
        'role_requis',
        'delai_cible_jours',
        'obligatoire',
    ];

    protected function casts(): array
    {
        return [
            'delai_cible_jours' => 'integer',
            'obligatoire' => 'boolean',
        ];
    }

    /**
     * Relations
     */
    public function workflowDefinition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class);
    }

    public function stepInstances(): HasMany
    {
        return $this->hasMany(WorkflowStepInstance::class, 'step_definition_id');
    }
}
