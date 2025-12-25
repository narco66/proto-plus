<?php

namespace Database\Factories;

use App\Models\DemandeBeneficiaire;
use App\Models\Demande;
use App\Models\User;
use App\Models\AyantDroit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemandeBeneficiaire>
 */
class DemandeBeneficiaireFactory extends Factory
{
    protected $model = DemandeBeneficiaire::class;

    public function definition(): array
    {
        $beneficiaireType = fake()->randomElement(['fonctionnaire', 'ayant_droit']);
        
        return [
            'demande_id' => Demande::factory(),
            'beneficiaire_type' => $beneficiaireType,
            'beneficiaire_id' => $beneficiaireType === 'fonctionnaire' 
                ? User::factory() 
                : AyantDroit::factory(),
            'role_dans_demande' => fake()->randomElement(['principal', 'accompagnant', 'autre']),
            'commentaire' => fake()->optional()->sentence(),
        ];
    }

    public function fonctionnaire(): static
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()->create();
            return [
                'beneficiaire_type' => 'fonctionnaire',
                'beneficiaire_id' => $user->id,
            ];
        });
    }

    public function ayantDroit(): static
    {
        return $this->state(function (array $attributes) {
            $ayantDroit = AyantDroit::factory()->create();
            return [
                'beneficiaire_type' => 'ayant_droit',
                'beneficiaire_id' => $ayantDroit->id,
            ];
        });
    }
}
