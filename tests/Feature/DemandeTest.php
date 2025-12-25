<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\User;
use App\Models\AyantDroit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DemandeTest extends TestCase
{
    use RefreshDatabase;

    protected User $fonctionnaire;
    protected User $agent;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les rôles et permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Créer un fonctionnaire
        $this->fonctionnaire = User::factory()->create();
        $this->fonctionnaire->assignRole('fonctionnaire');

        // Créer un agent
        $this->agent = User::factory()->create();
        $this->agent->assignRole('agent_protocole');
    }

    #[Test]
    public function un_fonctionnaire_peut_creer_une_demande()
    {
        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('demandes.create'));

        $response->assertStatus(200);
        $response->assertViewIs('demandes.create');
    }

    #[Test]
    public function un_fonctionnaire_peut_stocker_une_demande()
    {
        $ayantDroit = AyantDroit::factory()->create([
            'fonctionnaire_user_id' => $this->fonctionnaire->id,
        ]);

        $data = [
            'type_demande' => 'visa_diplomatique',
            'priorite' => 'normal',
            'motif' => 'Mission officielle',
            'date_depart_prevue' => now()->addDays(30)->format('Y-m-d'),
            'pays_destination' => 'France',
            'beneficiaires' => [
                [
                    'beneficiaire_type' => 'fonctionnaire',
                    'beneficiaire_id' => $this->fonctionnaire->id,
                    'role_dans_demande' => 'principal',
                ],
            ],
        ];

        $response = $this->actingAs($this->fonctionnaire)
            ->post(route('demandes.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('demandes', [
            'demandeur_user_id' => $this->fonctionnaire->id,
            'type_demande' => 'visa_diplomatique',
            'statut' => 'brouillon',
        ]);
    }

    #[Test]
    public function un_fonctionnaire_peut_voir_ses_propres_demandes()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('demandes.show', $demande));

        $response->assertStatus(200);
        $response->assertViewIs('demandes.show');
        $response->assertSee($demande->reference);
    }

    #[Test]
    public function un_fonctionnaire_ne_peut_pas_voir_les_demandes_des_autres()
    {
        $autreFonctionnaire = User::factory()->create();
        $autreFonctionnaire->assignRole('fonctionnaire');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $autreFonctionnaire->id,
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('demandes.show', $demande));

        $response->assertStatus(403);
    }

    #[Test]
    public function un_agent_peut_voir_toutes_les_demandes()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
        ]);

        $response = $this->actingAs($this->agent)
            ->get(route('demandes.show', $demande));

        $response->assertStatus(200);
    }

    #[Test]
    public function un_fonctionnaire_peut_modifier_une_demande_en_brouillon()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'brouillon',
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('demandes.edit', $demande));

        $response->assertStatus(200);
        $response->assertViewIs('demandes.edit');
    }

    #[Test]
    public function un_fonctionnaire_ne_peut_pas_modifier_une_demande_soumise()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('demandes.edit', $demande));

        $response->assertRedirect(route('demandes.show', $demande));
        $response->assertSessionHas('error');
    }

    #[Test]
    public function un_directeur_peut_corriger_une_demande_validee()
    {
        $directeur = User::factory()->create();
        $directeur->assignRole('directeur_protocole');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'valide',
        ]);

        $data = [
            'motif' => 'Correction post-validation',
            'date_depart_prevue' => now()->addDays(10)->format('Y-m-d'),
            'pays_destination' => 'Belgique',
            'type_demande' => $demande->type_demande,
        ];

        $response = $this->actingAs($directeur)
            ->put(route('demandes.update', $demande), $data);

        $response->assertRedirect(route('demandes.show', $demande));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'motif' => 'Correction post-validation',
        ]);

        $this->assertDatabaseHas('historique_demandes', [
            'demande_id' => $demande->id,
            'action' => 'modif',
            'commentaire' => 'Correction post-validation',
            'auteur_id' => $directeur->id,
        ]);
    }

    #[Test]
    public function un_fonctionnaire_peut_supprimer_une_demande_en_brouillon()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'brouillon',
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->delete(route('demandes.destroy', $demande));

        $response->assertRedirect(route('demandes.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('demandes', [
            'id' => $demande->id,
        ]);
    }

    #[Test]
    public function un_fonctionnaire_ne_peut_pas_supprimer_une_demande_soumise()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->delete(route('demandes.destroy', $demande));

        $response->assertRedirect(route('demandes.show', $demande));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
        ]);
    }

    #[Test]
    public function un_fonctionnaire_peut_soumettre_une_demande()
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'brouillon',
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->post(route('demandes.submit', $demande));

        $response->assertRedirect(route('demandes.show', $demande));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'soumis',
        ]);
    }

    #[Test]
    public function la_liste_des_demandes_est_paginee()
    {
        Demande::factory()->count(20)->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
        ]);

        $response = $this->actingAs($this->fonctionnaire)
            ->get(route('demandes.index'));

        $response->assertStatus(200);
        $response->assertViewHas('demandes');
    }
}
