<?php

namespace Database\Factories;

use App\Models\WorkflowInstance;
use App\Models\Demande;
use App\Models\WorkflowDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowInstance>
 */
class WorkflowInstanceFactory extends Factory
{
    protected $model = WorkflowInstance::class;

    public function definition(): array
    {
        $workflow = $this->standardWorkflowWithSteps();

        return [
            'demande_id' => Demande::factory(),
            'workflow_definition_id' => $workflow->id,
            'statut' => fake()->randomElement(['en_cours', 'termine', 'annule']),
            'started_at' => now(),
            'ended_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }

    public function enCours(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_cours',
            'ended_at' => null,
        ]);
    }

    public function termine(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'termine',
            'ended_at' => now(),
        ]);
    }

    public function avecWorkflowStandard(): static
    {
        return $this->state(function (array $attributes) {
            $workflow = $this->standardWorkflowWithSteps();
            return [
                'workflow_definition_id' => $workflow->id,
            ];
        });
    }

    protected function standardWorkflowWithSteps(): WorkflowDefinition
    {
        $workflow = WorkflowDefinition::firstOrCreate(
            ['code' => 'STANDARD'],
            [
                'libelle' => 'Workflow standard de validation',
                'actif' => true,
                'version' => 1,
            ]
        );

        if ($workflow->steps()->count() === 0) {
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
                    'libelle' => 'Validation niveau 3 - Secretaire General',
                    'role_requis' => 'secretaire_general',
                    'delai_cible_jours' => 3,
                    'obligatoire' => false,
                ],
            ];

            foreach ($steps as $step) {
                $workflow->steps()->firstOrCreate(
                    ['ordre' => $step['ordre']],
                    $step
                );
            }
        }

        return $workflow;
    }
}
