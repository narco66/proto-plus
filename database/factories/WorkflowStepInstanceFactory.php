<?php

namespace Database\Factories;

use App\Models\WorkflowStepInstance;
use App\Models\WorkflowInstance;
use App\Models\WorkflowStepDefinition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowStepInstance>
 */
class WorkflowStepInstanceFactory extends Factory
{
    protected $model = WorkflowStepInstance::class;

    public function definition(): array
    {
        return [
            'workflow_instance_id' => WorkflowInstance::factory(),
            'step_definition_id' => WorkflowStepDefinition::factory(),
            'statut' => fake()->randomElement(['a_faire', 'en_traitement', 'valide', 'rejete', 'retour_correction']),
            'assigned_user_id' => fake()->optional()->randomElement([User::factory(), null]),
            'decided_by' => fake()->optional()->randomElement([User::factory(), null]),
            'decision_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'commentaire' => fake()->optional()->sentence(),
        ];
    }

    public function aFaire(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'a_faire',
            'assigned_user_id' => null,
            'decided_by' => null,
            'decision_at' => null,
            'commentaire' => null,
        ]);
    }

    public function enTraitement(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_traitement',
            'assigned_user_id' => User::factory(),
        ]);
    }

    public function valide(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'valide',
            'decided_by' => User::factory(),
            'decision_at' => now(),
        ]);
    }

    public function rejete(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'rejete',
            'decided_by' => User::factory(),
            'decision_at' => now(),
            'commentaire' => fake()->sentence(),
        ]);
    }
}
