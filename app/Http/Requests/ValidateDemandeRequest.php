<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateDemandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $demande = $this->route('demande');
        
        // Vérifier que l'utilisateur peut valider cette demande
        $user = $this->user();
        $workflowInstance = $demande->workflowInstance;
        
        if (!$workflowInstance) {
            return false;
        }

        // Vérifier si l'utilisateur est admin ou peut voir toutes les demandes
        $canViewAll = $user->can('demandes.view_all') || $user->can('admin.access') || $user->hasRole('admin');
        
        // Vérifier qu'il y a une étape en attente
        if ($canViewAll) {
            // Admin peut valider n'importe quelle étape en attente
            $hasPendingStep = $workflowInstance->stepInstances()
                ->where('statut', 'a_faire')
                ->exists();
        } else {
            // Utilisateur normal : vérifier qu'il y a une étape en attente pour son rôle
            $userRoles = $user->getRoleNames();
            $hasPendingStep = $workflowInstance->stepInstances()
                ->where('statut', 'a_faire')
                ->whereHas('stepDefinition', function ($query) use ($userRoles) {
                    $query->whereIn('role_requis', $userRoles);
                })
                ->exists();
        }

        return $hasPendingStep;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'in:valide,rejete,retour_correction'],
            'commentaire' => ['required_if:decision,rejete,retour_correction', 'nullable', 'string', 'max:1000'],
            'level' => ['nullable', 'integer', 'in:1,2,3'],
        ];
    }

    public function messages(): array
    {
        return [
            'decision.required' => 'La décision est obligatoire.',
            'decision.in' => 'La décision doit être : valide, rejete ou retour_correction.',
            'commentaire.required_if' => 'Un commentaire est obligatoire en cas de rejet ou retour pour correction.',
        ];
    }
}
