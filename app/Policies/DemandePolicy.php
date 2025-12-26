<?php

namespace App\Policies;

use App\Models\Demande;
use App\Models\User;

class DemandePolicy
{
    private const POST_VALIDATION_STATUSES = ['valide', 'rejete'];
    private const VALIDATION_ROLES = [
        'admin',
        'secretaire_general',
        'directeur_protocole',
    ];
    private const SUBMISSION_ROLES = [
        'chef_service',
        'directeur_protocole',
        'secretaire_general',
        'directeur_SI',
        'admin',
    ];

    public function viewAny(User $user): bool
    {
        return $user->can('demandes.view_all') || $user->can('demandes.view_own');
    }

    public function view(User $user, Demande $demande): bool
    {
        // Le demandeur peut voir sa demande
        if ($demande->demandeur_user_id === $user->id) {
            return $user->can('demandes.view_own');
        }
        
        // Autres utilisateurs avec permission
        return $user->can('demandes.view_all');
    }

    public function create(User $user): bool
    {
        return $user->can('demandes.create');
    }

    public function update(User $user, Demande $demande): bool
    {
        // Le demandeur peut modifier si brouillon
        if ($demande->demandeur_user_id === $user->id && $demande->statut === 'brouillon') {
            return $user->can('demandes.create');
        }

        if (in_array($demande->statut, self::POST_VALIDATION_STATUSES, true)) {
            return $this->isValidationAdministrator($user);
        }

        // Agents peuvent modifier en instruction
        if ($demande->statut === 'en_cours' || $demande->statut === 'soumis') {
            return $user->can('demandes.edit');
        }

        return false;
    }

    private function isValidationAdministrator(User $user): bool
    {
        return $user->hasAnyRole(self::VALIDATION_ROLES);
    }

    public function delete(User $user, Demande $demande): bool
    {
        // Seulement si brouillon et propriétaire
        return $demande->statut === 'brouillon' 
            && $demande->demandeur_user_id === $user->id 
            && $user->can('demandes.delete');
    }

    public function submit(User $user, Demande $demande): bool
    {
        if ($demande->statut !== 'brouillon' || !$user->can('demandes.submit')) {
            return false;
        }

        if ($demande->demandeur_user_id === $user->id) {
            return true;
        }

        return $user->hasAnyRole(self::SUBMISSION_ROLES);
    }

    public function validateLevel1(User $user, Demande $demande): bool
    {
        return $user->can('demandes.validate_level_1') 
            && ($demande->statut === 'en_cours' || $demande->statut === 'soumis');
    }

    public function validateLevel2(User $user, Demande $demande): bool
    {
        return $user->can('demandes.validate_level_2') 
            && $demande->statut === 'valide'; // Après validation niveau 1
    }

    public function validateLevel3(User $user, Demande $demande): bool
    {
        return $user->can('demandes.validate_level_3') 
            && $demande->statut === 'valide'; // Après validation niveau 2
    }
}
