<?php

namespace Database\Seeders;

use App\Models\WorkflowDefinition;
use App\Models\WorkflowStepDefinition;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        // Workflow standard
        $workflow = WorkflowDefinition::firstOrCreate(
            ['code' => 'STANDARD'],
            [
                'libelle' => 'Workflow standard de validation',
                'actif' => true,
                'version' => 1,
            ]
        );

        // Étapes du workflow standard
        $steps = [
            [
                'ordre' => 1,
                'libelle' => 'Instruction par agent du Protocole',
                'role_requis' => 'agent_protocole',
                'delai_cible_jours' => 3,
                'obligatoire' => true,
            ],
            [
                'ordre' => 2,
                'libelle' => 'Validation niveau 1 - Chef de Service',
                'role_requis' => 'chef_service',
                'delai_cible_jours' => 2,
                'obligatoire' => true,
            ],
            [
                'ordre' => 3,
                'libelle' => 'Validation niveau 2 - Directeur du Protocole',
                'role_requis' => 'directeur_protocole',
                'delai_cible_jours' => 2,
                'obligatoire' => true,
            ],
            [
                'ordre' => 4,
                'libelle' => 'Validation niveau 3 - Secrétaire Général',
                'role_requis' => 'secretaire_general',
                'delai_cible_jours' => 3,
                'obligatoire' => false, // Optionnel selon type de demande
            ],
        ];

        foreach ($steps as $stepData) {
            WorkflowStepDefinition::firstOrCreate(
                [
                    'workflow_definition_id' => $workflow->id,
                    'ordre' => $stepData['ordre'],
                ],
                $stepData
            );
        }
    }
}
