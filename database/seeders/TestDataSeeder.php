<?php

namespace Database\Seeders;

use App\Models\AyantDroit;
use App\Models\Demande;
use App\Models\DemandeBeneficiaire;
use App\Models\User;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowInstance;
use App\Models\WorkflowStepInstance;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les utilisateurs de test
        $fonctionnaire = User::where('email', 'fonctionnaire@ceeac.org')->first();
        $admin = User::where('email', 'admin@ceeac.org')->first();

        if (!$fonctionnaire) {
            $this->command->warn('Utilisateur fonctionnaire non trouvé. Exécutez d\'abord UserSeeder.');
            return;
        }

        // Créer des ayants droit pour le fonctionnaire
        $ayantsDroit = AyantDroit::factory()->count(3)->create([
            'fonctionnaire_user_id' => $fonctionnaire->id,
            'status' => 'actif',
        ]);

        // Créer des demandes avec différents statuts
        $statuts = ['brouillon', 'soumis', 'en_cours', 'valide', 'rejete'];

        foreach ($statuts as $index => $statut) {
            $demande = Demande::factory()->create([
                'demandeur_user_id' => $fonctionnaire->id,
                'statut' => $statut,
                'type_demande' => $this->getRandomType(),
                'priorite' => $index % 2 === 0 ? 'normal' : 'urgent',
                'date_soumission' => $statut !== 'brouillon' ? now()->subDays(rand(1, 30)) : null,
            ]);

            // Ajouter le demandeur comme bénéficiaire principal
            DemandeBeneficiaire::create([
                'demande_id' => $demande->id,
                'beneficiaire_type' => 'fonctionnaire',
                'beneficiaire_id' => $fonctionnaire->id,
                'role_dans_demande' => 'principal',
            ]);

            // Ajouter un ayant droit si disponible
            if ($ayantsDroit->isNotEmpty() && $index % 2 === 0) {
                $ayantDroit = $ayantsDroit->random();
                DemandeBeneficiaire::create([
                    'demande_id' => $demande->id,
                    'beneficiaire_type' => 'ayant_droit',
                    'beneficiaire_id' => $ayantDroit->id,
                    'role_dans_demande' => 'secondaire',
                ]);
            }
            $this->createWorkflowIfNeeded($demande);
        }

        // Créer quelques demandes supplémentaires pour avoir plus de données
        for ($i = 0; $i < 10; $i++) {
            $demande = Demande::factory()->create([
                'demandeur_user_id' => $fonctionnaire->id,
                'statut' => $statuts[array_rand($statuts)],
                'type_demande' => $this->getRandomType(),
                'priorite' => rand(0, 1) === 0 ? 'normal' : 'urgent',
                'date_soumission' => rand(0, 1) === 0 ? now()->subDays(rand(1, 60)) : null,
            ]);

            // Ajouter le demandeur comme bénéficiaire
            DemandeBeneficiaire::create([
                'demande_id' => $demande->id,
                'beneficiaire_type' => 'fonctionnaire',
                'beneficiaire_id' => $fonctionnaire->id,
                'role_dans_demande' => 'principal',
            ]);
            $this->createWorkflowIfNeeded($demande);
        }

        // Si d'autres utilisateurs existent, créer des demandes pour eux aussi
        $autresUsers = User::where('email', '!=', 'fonctionnaire@ceeac.org')
            ->where('email', '!=', 'admin@ceeac.org')
            ->get();

        foreach ($autresUsers as $user) {
            for ($i = 0; $i < 3; $i++) {
                $demande = Demande::factory()->create([
                    'demandeur_user_id' => $user->id,
                    'statut' => $statuts[array_rand($statuts)],
                    'type_demande' => $this->getRandomType(),
                    'priorite' => 'normal',
                    'date_soumission' => rand(0, 1) === 0 ? now()->subDays(rand(1, 30)) : null,
                ]);

                DemandeBeneficiaire::create([
                    'demande_id' => $demande->id,
                    'beneficiaire_type' => 'fonctionnaire',
                    'beneficiaire_id' => $user->id,
                    'role_dans_demande' => 'principal',
                ]);
                $this->createWorkflowIfNeeded($demande);
            }
        }

        $this->command->info('Données de test créées avec succès !');
        $this->command->info('- ' . Demande::count() . ' demandes');
        $this->command->info('- ' . AyantDroit::count() . ' ayants droit');
    }

    protected function getRandomType(): string
    {
        $types = [
            'visa_diplomatique',
            'visa_courtoisie',
            'visa_familial',
            'carte_diplomatique',
            'carte_consulaire',
            'franchise_douaniere',
            'immatriculation_diplomatique',
            'autorisation_entree',
            'autorisation_sortie',
        ];

        return $types[array_rand($types)];
    }

    protected function createWorkflowIfNeeded(Demande $demande): void
    {
        if (!in_array($demande->statut, ['soumis', 'en_cours'])) {
            return;
        }

        $workflow = WorkflowDefinition::where('code', 'STANDARD')->first();

        if (!$workflow) {
            return;
        }

        $instance = WorkflowInstance::create([
            'demande_id' => $demande->id,
            'workflow_definition_id' => $workflow->id,
            'statut' => 'en_cours',
            'started_at' => now(),
        ]);

        foreach ($workflow->steps as $step) {
            WorkflowStepInstance::create([
                'workflow_instance_id' => $instance->id,
                'step_definition_id' => $step->id,
                'statut' => $step->ordre === 1 ? 'a_faire' : 'skipped',
                'assigned_role' => $step->role_requis,
            ]);
        }
    }
}

