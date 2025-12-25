<?php

namespace App\Services;

use App\Exceptions\DemandeException;
use App\Models\Demande;
use App\Models\DemandeBeneficiaire;
use App\Models\HistoriqueDemande;
use App\Models\User;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowInstance;
use App\Models\WorkflowStepInstance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemandeService
{
    private const POST_VALIDATION_STATUSES = ['valide', 'rejete'];
    private const VALIDATION_ROLES = ['admin', 'secretaire_general', 'directeur_protocole'];

    /**
     * Créer une nouvelle demande
     */
    public function create(array $data, $userId): Demande
    {
        return DB::transaction(function () use ($data, $userId) {
            $demande = Demande::create([
                'type_demande' => $data['type_demande'],
                'demandeur_user_id' => $userId,
                'statut' => 'brouillon',
                'motif' => $data['motif'] ?? null,
                'date_depart_prevue' => $data['date_depart_prevue'] ?? null,
                'pays_destination' => $data['pays_destination'] ?? null,
                'priorite' => $data['priorite'] ?? 'normal',
            ]);

            // Ajouter les bénéficiaires
            if (isset($data['beneficiaires']) && is_array($data['beneficiaires'])) {
                foreach ($data['beneficiaires'] as $beneficiaire) {
                    DemandeBeneficiaire::create([
                        'demande_id' => $demande->id,
                        'beneficiaire_type' => $beneficiaire['beneficiaire_type'],
                        'beneficiaire_id' => $beneficiaire['beneficiaire_id'],
                        'role_dans_demande' => $beneficiaire['role_dans_demande'] ?? 'principal',
                        'commentaire' => $beneficiaire['commentaire'] ?? null,
                    ]);
                }
            }

            // Historique
            HistoriqueDemande::create([
                'demande_id' => $demande->id,
                'action' => 'creation',
                'auteur_id' => $userId,
                'commentaire' => 'Demande créée',
                'created_at' => now(),
            ]);

            return $demande->fresh(['beneficiaires', 'demandeur']);
        });
    }

    /**
     * Soumettre une demande
     */
    public function submit(Demande $demande, $userId): Demande
    {
        if ($demande->statut !== 'brouillon') {
            throw new DemandeException('Seules les demandes en brouillon peuvent être soumises.', 422);
        }

        if ($demande->demandeur_user_id !== $userId) {
            throw new DemandeException('Vous ne pouvez soumettre que vos propres demandes.', 403);
        }

        return DB::transaction(function () use ($demande, $userId) {
            $demande->update([
                'statut' => 'soumis',
                'date_soumission' => now(),
            ]);

            // Créer l'instance de workflow
            $workflow = WorkflowDefinition::where('code', 'STANDARD')->where('actif', true)->first();
            if ($workflow) {
                $instance = WorkflowInstance::create([
                    'demande_id' => $demande->id,
                    'workflow_definition_id' => $workflow->id,
                    'statut' => 'en_cours',
                    'started_at' => now(),
                ]);

                // Créer les étapes - seule la première étape obligatoire est active
                $firstStepActivated = false;
                foreach ($workflow->steps()->orderBy('ordre')->get() as $step) {
                    // Activer seulement la première étape obligatoire
                    if (!$firstStepActivated && $step->obligatoire) {
                        WorkflowStepInstance::create([
                            'workflow_instance_id' => $instance->id,
                            'step_definition_id' => $step->id,
                            'statut' => 'a_faire',
                            'assigned_role' => $step->role_requis,
                        ]);
                        $firstStepActivated = true;
                    } else {
                        // Les autres étapes sont en attente (utiliser 'skipped' temporairement, sera activé lors de la validation précédente)
                        WorkflowStepInstance::create([
                            'workflow_instance_id' => $instance->id,
                            'step_definition_id' => $step->id,
                            'statut' => 'skipped', // Sera activé quand l'étape précédente sera validée
                            'assigned_role' => $step->role_requis,
                        ]);
                    }
                }
            }

            // Historique
            HistoriqueDemande::create([
                'demande_id' => $demande->id,
                'action' => 'soumission',
                'auteur_id' => $userId,
                'commentaire' => 'Demande soumise pour traitement',
                'created_at' => now(),
            ]);

            // Notifier les utilisateurs concernés par la première étape du workflow
            if ($workflow && isset($instance)) {
                $firstStep = $instance->stepInstances()->where('statut', 'a_faire')->first();
                if ($firstStep) {
                    try {
                        $roles = WorkflowNotificationHelper::resolveRoles($firstStep->assigned_role);
                        $users = \App\Models\User::role($roles)->get();
                        foreach ($users as $user) {
                            $user->notify(new \App\Notifications\DemandeEnAttenteValidation($demande, $firstStep));
                        }
                    } catch (\Exception $e) {
                        Log::error('Erreur lors de l\'envoi de notification après soumission', [
                            'error' => $e->getMessage(),
                            'demande_id' => $demande->id,
                        ]);
                    }
                }
            }

            return $demande->fresh(['workflowInstance', 'beneficiaires']);
        });
    }

    /**
     * Mettre à jour une demande
     */
    public function update(Demande $demande, array $data, $userId): Demande
    {
        $user = User::find($userId);
        $canModifyPostValidation = $user?->hasAnyRole(self::VALIDATION_ROLES) ?? false;
        $isPostValidation = in_array($demande->statut, self::POST_VALIDATION_STATUSES, true);

        if ($demande->statut !== 'brouillon') {
            if (!$isPostValidation || !$canModifyPostValidation) {
                throw new DemandeException('Seuls les administrateurs, secrétaires généraux et directeurs peuvent modifier une demande validée.', 403);
            }
        }

        return DB::transaction(function () use ($demande, $data, $userId, $isPostValidation, $user) {
            $demande->update([
                'type_demande' => $data['type_demande'] ?? $demande->type_demande,
                'motif' => $data['motif'] ?? $demande->motif,
                'date_depart_prevue' => $data['date_depart_prevue'] ?? $demande->date_depart_prevue,
                'pays_destination' => $data['pays_destination'] ?? $demande->pays_destination,
                'priorite' => $data['priorite'] ?? $demande->priorite,
            ]);

            // Mettre à jour les bénéficiaires si fournis
            if (isset($data['beneficiaires'])) {
                $demande->beneficiaires()->delete();
                foreach ($data['beneficiaires'] as $beneficiaire) {
                    DemandeBeneficiaire::create([
                        'demande_id' => $demande->id,
                        'beneficiaire_type' => $beneficiaire['beneficiaire_type'],
                        'beneficiaire_id' => $beneficiaire['beneficiaire_id'],
                        'role_dans_demande' => $beneficiaire['role_dans_demande'] ?? 'principal',
                        'commentaire' => $beneficiaire['commentaire'] ?? null,
                    ]);
                }
            }

            // Historique
            HistoriqueDemande::create([
                'demande_id' => $demande->id,
                'action' => 'modif',
                'auteur_id' => $userId,
                'commentaire' => $isPostValidation ? 'Correction post-validation' : 'Demande modifiée',
                'created_at' => now(),
            ]);

            if ($isPostValidation) {
                Log::info('Demande modifiée après validation', [
                    'demande_id' => $demande->id,
                    'user_id' => $userId,
                    'roles' => $user ? $user->getRoleNames()->toArray() : [],
                    'statut' => $demande->statut,
                ]);
            }

            return $demande->fresh(['beneficiaires']);
        });
    }
}
