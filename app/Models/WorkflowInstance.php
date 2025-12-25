<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'workflow_definition_id',
        'statut',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function workflowDefinition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class);
    }

    public function stepInstances(): HasMany
    {
        return $this->hasMany(WorkflowStepInstance::class, 'workflow_instance_id');
    }
}
