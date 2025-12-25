<?php

namespace Database\Factories;

use App\Models\WorkflowDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowDefinition>
 */
class WorkflowDefinitionFactory extends Factory
{
    protected $model = WorkflowDefinition::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('WF-####')),
            'libelle' => fake()->sentence(4),
            'actif' => true,
            'version' => 1,
        ];
    }

    public function standard(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'STANDARD',
            'libelle' => 'Workflow standard de validation',
            'actif' => true,
            'version' => 1,
        ]);
    }

    public function inactif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }
}
