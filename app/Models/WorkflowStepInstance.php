<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStepInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_instance_id',
        'step_definition_id',
        'statut',
        'assigned_role',
        'assigned_user_id',
        'decided_by',
        'decision_at',
        'commentaire',
    ];

    protected function casts(): array
    {
        return [
            'decision_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function stepDefinition(): BelongsTo
    {
        return $this->belongsTo(WorkflowStepDefinition::class, 'step_definition_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
