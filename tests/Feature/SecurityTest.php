<?php

namespace Tests\Feature;

use App\Models\AyantDroit;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    #[Test]
    public function les_routes_protegees_redirigent_vers_login(): void
    {
        $response = $this->get(route('demandes.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function un_utilisateur_non_autorise_ne_peut_pas_acceder_aux_exports(): void
    {
        $fonctionnaire = User::factory()->create();
        $fonctionnaire->assignRole('fonctionnaire');

        $response = $this->actingAs($fonctionnaire)
            ->get(route('exports.demandes.excel'));

        $response->assertStatus(403);
    }

    #[Test]
    public function un_utilisateur_ne_peut_pas_modifier_une_demande_d_un_autre(): void
    {
        $fonctionnaire1 = User::factory()->create();
        $fonctionnaire1->assignRole('fonctionnaire');

        $fonctionnaire2 = User::factory()->create();
        $fonctionnaire2->assignRole('fonctionnaire');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $fonctionnaire2->id,
            'statut' => 'brouillon',
        ]);

        $data = [
            'type_demande' => 'visa_diplomatique',
            'priorite' => 'urgent',
            'motif' => 'Tentative de modification non autorisee',
        ];

        $response = $this->actingAs($fonctionnaire1)
            ->put(route('demandes.update', $demande), $data);

        $response->assertStatus(403);
    }

    #[Test]
    public function un_utilisateur_ne_peut_pas_supprimer_un_ayant_droit_d_un_autre(): void
    {
        $fonctionnaire1 = User::factory()->create();
        $fonctionnaire1->assignRole('fonctionnaire');

        $fonctionnaire2 = User::factory()->create();
        $fonctionnaire2->assignRole('fonctionnaire');

        $ayantDroit = AyantDroit::factory()->create([
            'fonctionnaire_user_id' => $fonctionnaire2->id,
        ]);

        $response = $this->actingAs($fonctionnaire1)
            ->delete(route('ayants-droit.destroy', $ayantDroit));

        $response->assertStatus(403);
    }

    #[Test]
    public function les_donnees_sont_echappees_dans_les_vues(): void
    {
        $fonctionnaire = User::factory()->create();
        $fonctionnaire->assignRole('fonctionnaire');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $fonctionnaire->id,
            'motif' => '<script>alert(\"XSS\")</script>',
        ]);

        $response = $this->actingAs($fonctionnaire)
            ->get(route('demandes.show', $demande));

        $response->assertStatus(200);
        $response->assertDontSee('alert("XSS")', false);
    }

    #[Test]
    public function les_requetes_csrf_sont_protegees(): void
    {
        $fonctionnaire = User::factory()->create();
        $fonctionnaire->assignRole('fonctionnaire');

        $response = $this->actingAs($fonctionnaire)
            ->post(route('demandes.store'), [
                'type_demande' => 'visa_diplomatique',
            ]);

        $response->assertStatus(419);
    }

    #[Test]
    public function les_utilisateurs_peuvent_seulement_voir_leurs_propres_demandes(): void
    {
        $fonctionnaire1 = User::factory()->create();
        $fonctionnaire1->assignRole('fonctionnaire');

        $fonctionnaire2 = User::factory()->create();
        $fonctionnaire2->assignRole('fonctionnaire');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $fonctionnaire2->id,
        ]);

        $response = $this->actingAs($fonctionnaire1)
            ->get(route('demandes.index'));

        $response->assertStatus(200);
        $response->assertDontSee($demande->reference);
    }
}
