<?php

namespace Database\Factories;

use App\Models\HistoriqueDemande;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoriqueDemande>
 */
class HistoriqueDemandeFactory extends Factory
{
    protected $model = HistoriqueDemande::class;

    public function definition(): array
    {
        return [
            'demande_id' => Demande::factory(),
            'action' => fake()->randomElement([
                'creation',
                'modification',
                'soumission',
                'validation',
                'rejet',
                'retour_correction',
                'annulation',
            ]),
            'auteur_id' => User::factory(),
            'commentaire' => fake()->optional()->sentence(),
            'created_at' => now(),
        ];
    }

    public function creation(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'creation',
        ]);
    }

    public function soumission(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'soumission',
        ]);
    }

    public function validation(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'validation',
            'commentaire' => fake()->sentence(),
        ]);
    }

    public function rejet(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'rejet',
            'commentaire' => fake()->sentence(),
        ]);
    }
}
