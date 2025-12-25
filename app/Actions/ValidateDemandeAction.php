<?php

namespace App\Actions;

use App\Exceptions\PermissionException;
use App\Exceptions\WorkflowException;
use App\Models\Demande;
use App\Models\WorkflowStepInstance;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\DB;

class ValidateDemandeAction
{
    public function __construct(
        protected WorkflowService $workflowService
    ) {}

    /**
     * Valider une demande au niveau approprié
     */
    public function execute(
        Demande $demande,
        $userId,
        string $decision,
        ?string $commentaire = null,
        ?int $level = null
    ): void {
        // Trouver l'étape en attente pour cet utilisateur
        $workflowInstance = $demande->workflowInstance;
        if (!$workflowInstance) {
            throw new WorkflowException('Aucun workflow actif pour cette demande.', 404);
        }

        $user = \App\Models\User::find($userId);
        $userRoles = $user->getRoleNames();

        // Vérifier si l'utilisateur est admin ou peut voir toutes les demandes
        $canViewAll = $user->can('demandes.view_all') || $user->can('admin.access') || $user->hasRole('admin');
        
        // Trouver l'étape en attente
        if ($canViewAll) {
            // Admin peut valider n'importe quelle étape en attente
            // Récupérer toutes les étapes en attente et les trier par ordre
            $stepInstance = $workflowInstance->stepInstances()
                ->where('statut', 'a_faire')
                ->with('stepDefinition')
                ->get()
                ->sortBy('stepDefinition.ordre')
                ->first();
        } else {
            // Utilisateur normal : trouver l'étape correspondant à son rôle
            $stepInstance = $workflowInstance->stepInstances()
                ->where('statut', 'a_faire')
                ->whereHas('stepDefinition', function ($query) use ($userRoles) {
                    $query->whereIn('role_requis', $userRoles);
                })
                ->with('stepDefinition')
                ->get()
                ->sortBy('stepDefinition.ordre')
                ->first();
        }

        if (!$stepInstance) {
            throw new WorkflowException('Aucune étape en attente de validation pour votre rôle.', 404);
        }

        // Vérifier que l'utilisateur a le rôle requis (sauf admin)
        if (!$canViewAll && !$userRoles->contains($stepInstance->assigned_role)) {
            throw new PermissionException('Vous n\'avez pas le rôle requis pour valider cette étape. Rôle requis : ' . ucfirst(str_replace('_', ' ', $stepInstance->assigned_role)) . '.');
        }

        // Vérifier les permissions selon le niveau (optionnel, pour validation supplémentaire)
        if ($level !== null) {
            if ($level === 1 && !$user->can('demandes.validate_level_1')) {
                throw new PermissionException('Vous n\'avez pas la permission de valider au niveau 1.');
            }
            if ($level === 2 && !$user->can('demandes.validate_level_2')) {
                throw new PermissionException('Vous n\'avez pas la permission de valider au niveau 2.');
            }
            if ($level === 3 && !$user->can('demandes.validate_level_3')) {
                throw new PermissionException('Vous n\'avez pas la permission de valider au niveau 3.');
            }
        }

        // Assigner l'utilisateur à l'étape si pas déjà assigné
        if (!$stepInstance->assigned_user_id) {
            $stepInstance->update([
                'assigned_user_id' => $userId,
            ]);
        }

        // Valider l'étape
        $this->workflowService->validateStep($stepInstance, $userId, $decision, $commentaire);
    }
}

