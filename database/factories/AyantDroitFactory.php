<?php

namespace Database\Factories;

use App\Models\AyantDroit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AyantDroit>
 */
class AyantDroitFactory extends Factory
{
    protected $model = AyantDroit::class;

    public function definition(): array
    {
        return [
            'fonctionnaire_user_id' => User::factory(),
            'civilite' => fake()->randomElement(['M.', 'Mme', 'Mlle']),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->optional()->dateTimeBetween('-60 years', '-18 years'),
            'lieu_naissance' => fake()->optional()->city(),
            'lien_familial' => fake()->randomElement(['conjoint', 'enfant', 'autre']),
            'nationalite' => fake()->optional()->country(),
            'passeport_num' => fake()->optional()->bothify('??#######'),
            'passeport_expire_at' => fake()->optional()->dateTimeBetween('now', '+5 years'),
            'status' => 'actif',
        ];
    }

    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'actif',
        ]);
    }
}
