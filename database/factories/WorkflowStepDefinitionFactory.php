<?php

namespace Database\Factories;

use App\Models\WorkflowStepDefinition;
use App\Models\WorkflowDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowStepDefinition>
 */
class WorkflowStepDefinitionFactory extends Factory
{
    protected $model = WorkflowStepDefinition::class;

    public function definition(): array
    {
        return [
            'workflow_definition_id' => WorkflowDefinition::factory(),
            'ordre' => fake()->numberBetween(1, 5),
            'libelle' => fake()->sentence(3),
            'role_requis' => fake()->randomElement([
                'agent_protocole',
                'chef_service',
                'directeur_protocole',
                'secretaire_general',
            ]),
            'delai_cible_jours' => fake()->numberBetween(1, 5),
            'obligatoire' => fake()->boolean(80), // 80% de chance d'être obligatoire
        ];
    }

    public function obligatoire(): static
    {
        return $this->state(fn (array $attributes) => [
            'obligatoire' => true,
        ]);
    }

    public function optionnel(): static
    {
        return $this->state(fn (array $attributes) => [
            'obligatoire' => false,
        ]);
    }
}
