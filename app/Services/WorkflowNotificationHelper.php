<?php

namespace App\Services;

class WorkflowNotificationHelper
{
    /**
     * Resolve the list of roles that should receive workflow notifications
     * when a step is ready for validation.
     */
    public static function resolveRoles(string $assignedRole): array
    {
        return match ($assignedRole) {
            'chef_service' => ['chef_service', 'chef_service_protocole'],
            'chef_service_protocole' => ['chef_service_protocole', 'chef_service'],
            default => [$assignedRole],
        };
    }
}
