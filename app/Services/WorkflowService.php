<?php

namespace App\Services;

use App\Exceptions\WorkflowException;
use App\Models\Demande;
use App\Models\User;
use App\Models\WorkflowInstance;
use App\Models\WorkflowStepInstance;
use App\Models\HistoriqueDemande;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkflowService
{
    private const LETTER_ROLES = [
        'directeur_protocole',
        'secretaire_general',
    ];

    /**
     * Valider une étape du workflow
     */
    public function validateStep(
        WorkflowStepInstance $stepInstance,
        $userId,
        string $decision,
        ?string $commentaire = null
    ): void {
        if (!in_array($decision, ['valide', 'rejete', 'retour_correction'])) {
            throw new WorkflowException('La décision doit être : valide, rejete ou retour_correction.', 422);
        }

        DB::transaction(function () use ($stepInstance, $userId, $decision, $commentaire) {
            // Mettre à jour le statut de l'étape
            $stepInstance->update([
                'statut' => $decision,
                'decided_by' => $userId,
                'decision_at' => now(),
                'commentaire' => $commentaire,
            ]);

            // Recharger les relations pour avoir les données à jour
            $stepInstance->refresh();
            $workflowInstance = $stepInstance->workflowInstance()->with('demande')->first();
            $demande = $workflowInstance->demande;
            $validator = User::find($userId);

            // Mettre à jour le statut de la demande
            if ($decision === 'valide') {
                if ($this->shouldGenerateLetter($stepInstance, $validator)) {
                    $this->generateAutorisationLetter($demande, $validator);
                }
                // Vérifier s'il y a une étape suivante obligatoire
                $nextStep = $this->getNextStep($stepInstance);
                if ($nextStep) {
                    // Activer l'étape suivante
                    $nextStep->update(['statut' => 'a_faire']);
                    $demande->update(['statut' => 'en_cours']);
                    
                    // Envoyer une notification à l'utilisateur concerné par l'étape suivante
                    $this->notifyNextStep($nextStep, $demande);
                } else {
                    // Toutes les étapes obligatoires sont validées
                    $demande->update([
                        'statut' => 'valide',
                        'date_validation' => now(),
                    ]);
                    $stepInstance->workflowInstance->update([
                        'statut' => 'termine',
                        'ended_at' => now(),
                    ]);
                    
                    // Notifier le demandeur
                    $this->notifyDemandeur($demande, 'valide');
                }
            } elseif ($decision === 'rejete') {
                $demande->update([
                    'statut' => 'rejete',
                    'date_rejet' => now(),
                    'motif_rejet' => $commentaire,
                ]);
                $stepInstance->workflowInstance->update([
                    'statut' => 'termine',
                    'ended_at' => now(),
                ]);
                
                // Notifier le demandeur
                $this->notifyDemandeur($demande, 'rejete');
            } elseif ($decision === 'retour_correction') {
                $demande->update(['statut' => 'brouillon']); // Retour en brouillon pour correction
                
                // Notifier le demandeur
                $this->notifyDemandeur($demande, 'retour_correction');
            }

            // Historique
            HistoriqueDemande::create([
                'demande_id' => $demande->id,
                'action' => $decision === 'valide' ? 'validation' : ($decision === 'rejete' ? 'rejet' : 'retour_correction'),
                'auteur_id' => $userId,
                'commentaire' => $commentaire,
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Obtenir l'étape suivante
     */
    protected function getNextStep(WorkflowStepInstance $currentStep): ?WorkflowStepInstance
    {
        $currentOrder = $currentStep->stepDefinition->ordre;
        $workflowInstance = $currentStep->workflowInstance;

        // Chercher la prochaine étape obligatoire
        $nextStepDefinition = $workflowInstance->workflowDefinition
            ->steps()
            ->where('ordre', '>', $currentOrder)
            ->where('obligatoire', true)
            ->orderBy('ordre')
            ->first();

        if (!$nextStepDefinition) {
            return null;
        }

        // Récupérer l'instance de l'étape suivante
        $nextStepInstance = $workflowInstance->stepInstances()
            ->where('step_definition_id', $nextStepDefinition->id)
            ->first();

        return $nextStepInstance;
    }

    /**
     * Notifier l'utilisateur concerné par l'étape suivante
     */
    protected function notifyNextStep(WorkflowStepInstance $nextStep, Demande $demande): void
    {
        try {
            $roles = WorkflowNotificationHelper::resolveRoles($nextStep->assigned_role);
            $users = \App\Models\User::role($roles)->get();
            
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\DemandeEnAttenteValidation($demande, $nextStep));
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de notification workflow', [
                'error' => $e->getMessage(),
                'demande_id' => $demande->id,
                'step_id' => $nextStep->id,
            ]);
        }
    }

    /**
     * Notifier le demandeur d'un changement de statut
     */
    protected function notifyDemandeur(Demande $demande, string $action): void
    {
        try {
            $demandeur = $demande->demandeur;
            if ($demandeur) {
                $demandeur->notify(new \App\Notifications\DemandeStatutChanged($demande, $action));
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de notification au demandeur', [
                'error' => $e->getMessage(),
                'demande_id' => $demande->id,
                'action' => $action,
            ]);
        }
    }

    /**
     * Obtenir les demandes en attente de validation pour un utilisateur
     */
    public function getPendingDemandesForUser($userId, $role): \Illuminate\Database\Eloquent\Collection
    {
        return Demande::whereHas('workflowInstance.stepInstances', function ($query) use ($userId, $role) {
            $query->where('statut', 'a_faire')
                ->where('assigned_role', $role);
        })
        ->whereIn('statut', ['soumis', 'en_cours'])
        ->with(['demandeur', 'workflowInstance.stepInstances.stepDefinition'])
        ->get();
    }

    protected function shouldGenerateLetter(WorkflowStepInstance $stepInstance, ?User $validator): bool
    {
        if (!$validator) {
            return false;
        }

        return in_array($stepInstance->assigned_role, self::LETTER_ROLES, true)
            || $validator->hasRole('admin');
    }

    protected function generateAutorisationLetter(Demande $demande, User $validator): void
    {
        try {
            $filename = 'lettres/autorisation_' . Str::slug($demande->reference) . '_' . now()->format('YmdHis') . '.pdf';

            $pdf = Pdf::loadView('letters.autorisation', [
                'demande' => $demande->fresh(['demandeur']),
                'validator' => $validator,
                'dateGenerated' => now()->format('d/m/Y'),
                'logo' => public_path('images/logo-ceeac.png'),
            ])->setPaper('a4');

            Storage::disk('public')->put($filename, $pdf->output());
            Log::info('Lettre d\'autorisation générée', [
                'demande_id' => $demande->id,
                'validator_id' => $validator->id,
                'path' => $filename,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de la lettre d\'autorisation', [
                'demande_id' => $demande->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
