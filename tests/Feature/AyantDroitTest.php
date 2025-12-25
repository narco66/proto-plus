<?php

namespace Tests\Feature;

use App\Models\AyantDroit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AyantDroitTest extends TestCase
{
    use RefreshDatabase;

    protected User $fonctionnaire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->fonctionnaire = User::factory()->create();
        $this->fonctionnaire->assignRole('fonctionnaire');
    }

    #[Test]
    public function un_fonctionnaire_peut_creer_un_ayant_droit()
    {
        $data = [
            'civilite' => 'Mme',
            'nom' => 'Dupont',
            'prenom' => 'Marie',
            'date_naissance' => '1990-01-15',
            'lien_familial' => 'conjoint',
            'nationalite' => 'Française',
        ];

        $response = $this->actingAs($this->fonctionnaire)
            ->post(route('ayants-droit.store'), $data);

        $response->assertRedirect(route('ayants-droit.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ayant_droits', [
            'fonctionnaire_user_id' => $this->fonctionnaire->id,
            'nom' => 'Dupont',
            'prenom' => 'Marie',
        ]);
    }

    #[Test]
    public function un_fonctionnaire_peut_voir_ses_ayants_droit()
    {
        $ayantDroit = AyantDroit::factory()->create([
            'fonctionnaire_user_id' => $this->fonctionnaire->id,
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('ayants-droit.show', $ayantDroit));

        $response->assertStatus(200);
        $response->assertViewIs('ayants-droit.show');
        $response->assertSee($ayantDroit->nom);
    }

    #[Test]
    public function un_fonctionnaire_ne_peut_pas_voir_les_ayants_droit_des_autres()
    {
        $autreFonctionnaire = User::factory()->create();
        $autreFonctionnaire->assignRole('fonctionnaire');

        $ayantDroit = AyantDroit::factory()->create([
            'fonctionnaire_user_id' => $autreFonctionnaire->id,
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('ayants-droit.show', $ayantDroit));

        $response->assertStatus(403);
    }

    #[Test]
    public function un_fonctionnaire_peut_modifier_ses_ayants_droit()
    {
        $ayantDroit = AyantDroit::factory()->create([
            'fonctionnaire_user_id' => $this->fonctionnaire->id,
        ]);

        $data = [
            'nom' => 'Martin',
            'prenom' => $ayantDroit->prenom,
            'civilite' => $ayantDroit->civilite,
            'lien_familial' => $ayantDroit->lien_familial,
        ];

        $response = $this->actingAs($this->fonctionnaire)
            ->put(route('ayants-droit.update', $ayantDroit), $data);

        $response->assertRedirect(route('ayants-droit.show', $ayantDroit));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ayant_droits', [
            'id' => $ayantDroit->id,
            'nom' => 'Martin',
        ]);
    }

    #[Test]
    public function un_fonctionnaire_peut_supprimer_ses_ayants_droit()
    {
        $ayantDroit = AyantDroit::factory()->create([
            'fonctionnaire_user_id' => $this->fonctionnaire->id,
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->delete(route('ayants-droit.destroy', $ayantDroit));

        $response->assertRedirect(route('ayants-droit.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('ayant_droits', [
            'id' => $ayantDroit->id,
        ]);
    }

    #[Test]
    public function la_validation_requiert_les_champs_obligatoires()
    {
        $data = [
            'nom' => '', // Champ vide
            'prenom' => 'Marie',
        ];

        $response = $this->actingAs($this->fonctionnaire)
            ->post(route('ayants-droit.store'), $data);

        $response->assertSessionHasErrors('nom');
    }
}
