<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Demande;
use App\Models\User;
use App\Models\AyantDroit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $beneficiaireType = fake()->randomElement(['fonctionnaire', 'ayant_droit']);
        
        return [
            'demande_id' => Demande::factory(),
            'type_document' => fake()->randomElement([
                'passeport',
                'carte_identite',
                'acte_naissance',
                'justificatif_domicile',
                'photo_identite',
                'autre',
            ]),
            'nom_fichier' => fake()->word() . '.' . fake()->randomElement(['pdf', 'jpg', 'png']),
            'chemin_fichier' => 'documents/' . fake()->uuid() . '.pdf',
            'taille' => fake()->numberBetween(10000, 5000000),
            'mime_type' => fake()->randomElement(['application/pdf', 'image/jpeg', 'image/png']),
            'titre' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'checksum' => fake()->sha256(),
            'beneficiaire_type' => $beneficiaireType,
            'beneficiaire_id' => $beneficiaireType === 'fonctionnaire' 
                ? User::factory() 
                : AyantDroit::factory(),
            'created_by' => User::factory(),
            'version' => 1,
        ];
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'nom_fichier' => fake()->word() . '.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'nom_fichier' => fake()->word() . '.' . fake()->randomElement(['jpg', 'png']),
            'mime_type' => fake()->randomElement(['image/jpeg', 'image/png']),
        ]);
    }
}
