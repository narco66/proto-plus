<?php

namespace Database\Factories;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Demande>
 */
class DemandeFactory extends Factory
{
    protected $model = Demande::class;

    public function definition(): array
    {
        return [
            'demandeur_user_id' => User::factory(),
            'reference' => null, // Sera généré automatiquement par le modèle
            'type_demande' => fake()->randomElement([
                'visa_diplomatique',
                'visa_courtoisie',
                'visa_familial',
                'carte_diplomatique',
                'carte_consulaire',
                'franchise_douaniere',
                'immatriculation_diplomatique',
                'autorisation_entree',
                'autorisation_sortie',
            ]),
            'statut' => fake()->randomElement(['brouillon', 'soumis', 'en_cours', 'valide', 'rejete']),
            'priorite' => fake()->randomElement(['normal', 'urgent']),
            'motif' => fake()->sentence(),
            'date_depart_prevue' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'pays_destination' => fake()->country(),
            'date_soumission' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'date_validation' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function brouillon(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'brouillon',
            'date_soumission' => null,
        ]);
    }

    public function soumis(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'soumis',
            'date_soumission' => now(),
        ]);
    }

    public function valide(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'valide',
            'date_soumission' => now()->subDays(5),
            'date_validation' => now()->subDays(2),
        ]);
    }
}
