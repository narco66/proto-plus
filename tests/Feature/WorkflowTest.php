<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\User;
use App\Models\WorkflowInstance;
use App\Models\WorkflowStepInstance;
use App\Services\DemandeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Notifications\DemandeEnAttenteValidation;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $fonctionnaire;
    protected User $chefService;
    protected User $directeur;
    protected DemandeService $demandeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
        $this->artisan('db:seed', ['--class' => 'WorkflowSeeder']);

        $this->fonctionnaire = User::factory()->create();
        $this->fonctionnaire->assignRole('fonctionnaire');

        $this->chefService = User::factory()->create();
        $this->chefService->assignRole('chef_service');

        $this->directeur = User::factory()->create();
        $this->directeur->assignRole('directeur_protocole');

        $this->demandeService = app(DemandeService::class);
    }

    #[Test]
    public function la_soumission_d_une_demande_cree_une_instance_de_workflow(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'brouillon',
        ]);

        $this->demandeService->submit($demande, $this->fonctionnaire->id);

        $this->assertDatabaseHas('workflow_instances', [
            'demande_id' => $demande->id,
            'statut' => 'en_cours',
        ]);
    }

    #[Test]
    public function un_chef_service_peut_voir_les_demandes_a_valider(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $workflowInstance = WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $stepDefinition = $workflowInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();

        if ($stepDefinition) {
            WorkflowStepInstance::factory()->create([
                'workflow_instance_id' => $workflowInstance->id,
                'step_definition_id' => $stepDefinition->id,
                'statut' => 'a_faire',
            ]);
        }

        $response = $this->actingAs($this->chefService)
            ->get(route('workflow.index'));

        $response->assertStatus(200);
        $response->assertViewIs('workflow.index');
    }

    #[Test]
    public function un_chef_service_peut_valider_une_demande(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $workflowInstance = WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $stepDefinition = $workflowInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();

        $stepInstance = WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $workflowInstance->id,
            'step_definition_id' => $stepDefinition->id,
            'statut' => 'a_faire',
        ]);

        $data = [
            'decision' => 'valide',
            'commentaire' => 'Demande conforme',
        ];

        $response = $this->actingAs($this->chefService)
            ->post(route('workflow.validate', $demande), $data);

        $response->assertRedirect(route('workflow.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('workflow_step_instances', [
            'id' => $stepInstance->id,
            'statut' => 'valide',
            'decided_by' => $this->chefService->id,
        ]);
    }

    #[Test]
    public function un_chef_service_peut_rejeter_une_demande(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $workflowInstance = WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $stepDefinition = $workflowInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();

        $stepInstance = WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $workflowInstance->id,
            'step_definition_id' => $stepDefinition->id,
            'statut' => 'a_faire',
        ]);

        $data = [
            'decision' => 'rejete',
            'commentaire' => 'Documents incomplets',
        ];

        $response = $this->actingAs($this->chefService)
            ->post(route('workflow.validate', $demande), $data);

        $response->assertRedirect(route('workflow.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'rejete',
        ]);
    }

    #[Test]
    public function un_fonctionnaire_ne_peut_pas_valider_une_demande(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $data = [
            'decision' => 'valide',
            'commentaire' => 'Test',
        ];

        $response = $this->actingAs($this->fonctionnaire)
            ->post(route('workflow.validate', $demande), $data);

        $response->assertStatus(403);
    }

    #[Test]
    public function le_rejet_requiert_un_commentaire(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $workflowInstance = WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $stepDefinition = $workflowInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();

        WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $workflowInstance->id,
            'step_definition_id' => $stepDefinition->id,
            'statut' => 'a_faire',
        ]);

        $data = [
            'decision' => 'rejete',
            'commentaire' => '',
        ];

        $response = $this->actingAs($this->chefService)
            ->post(route('workflow.validate', $demande), $data);

        $response->assertSessionHasErrors('commentaire');
    }

    #[Test]
    public function consulter_le_workflow_marque_la_notification_comme_lue(): void
    {
        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $workflowInstance = WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $stepDefinition = $workflowInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();

        $stepInstance = WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $workflowInstance->id,
            'step_definition_id' => $stepDefinition->id,
            'statut' => 'a_faire',
        ]);

        $this->chefService->notify(new DemandeEnAttenteValidation($demande, $stepInstance));
        $this->assertSame(1, $this->chefService->unreadNotifications()->count());

        $response = $this->actingAs($this->chefService)
            ->get(route('workflow.show', $demande));

        $response->assertStatus(200);
        $this->assertSame(0, $this->chefService->fresh()->unreadNotifications()->count());
    }

    #[Test]
    public function la_cloche_ne_contient_pas_de_demandes_deja_validees(): void
    {
        $pending = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $validated = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'valide',
        ]);

        $pendingInstance = WorkflowInstance::factory()->create(['demande_id' => $pending->id]);
        $stepDefinition = $pendingInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();
        $pendingStep = WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $pendingInstance->id,
            'step_definition_id' => $stepDefinition->id,
            'statut' => 'a_faire',
        ]);

        $validatedInstance = WorkflowInstance::factory()->create(['demande_id' => $validated->id]);
        $stepDefinitionValidated = $validatedInstance->workflowDefinition->steps()
            ->where('role_requis', 'chef_service')
            ->first();
        $validatedStep = WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $validatedInstance->id,
            'step_definition_id' => $stepDefinitionValidated->id,
            'statut' => 'valide',
        ]);

        $this->chefService->notify(new DemandeEnAttenteValidation($pending, $pendingStep));
        $this->chefService->notify(new DemandeEnAttenteValidation($validated, $validatedStep));

        $this->assertSame(2, $this->chefService->unreadNotifications()->count());

        $response = $this->actingAs($this->chefService)
            ->get(route('workflow.index'));

        $response->assertStatus(200);
        $this->assertSame(1, $this->chefService->fresh()->unreadNotifications()->count());
    }

    #[Test]
    public function une_lettre_est_generée_apres_validation_du_directeur(): void
    {
        Storage::fake('public');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'soumis',
        ]);

        $workflowInstance = WorkflowInstance::factory()->create([
            'demande_id' => $demande->id,
        ]);

        $stepDefinition = $workflowInstance->workflowDefinition->steps()
            ->where('role_requis', 'directeur_protocole')
            ->first();

        $stepInstance = WorkflowStepInstance::factory()->create([
            'workflow_instance_id' => $workflowInstance->id,
            'step_definition_id' => $stepDefinition->id,
            'statut' => 'a_faire',
            'assigned_role' => 'directeur_protocole',
        ]);

        $data = [
            'decision' => 'valide',
            'commentaire' => 'Validation finale',
        ];

        $this->actingAs($this->directeur)
            ->post(route('workflow.validate', $demande), $data);

        $files = Storage::disk('public')->files('lettres');
        $this->assertCount(1, $files);
        $this->assertStringContainsString('autorisation_', $files[0]);
    }

    #[Test]
    public function un_chemin_note_verbale_est_disponible_pour_un_visa(): void
    {
        Storage::fake('public');

        $demande = Demande::factory()->create([
            'demandeur_user_id' => $this->fonctionnaire->id,
            'statut' => 'valide',
            'type_demande' => 'visa_courtoisie',
        ]);

        $response = $this->actingAs($this->directeur)
            ->get(route('demandes.letter', $demande));

        $response->assertHeader('content-type', 'application/pdf');
    }
}
