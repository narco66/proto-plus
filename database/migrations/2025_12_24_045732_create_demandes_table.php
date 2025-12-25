<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->enum('type_demande', [
                'visa_diplomatique',
                'visa_courtoisie',
                'visa_familial',
                'carte_diplomatique',
                'carte_consulaire',
                'franchise_douaniere',
                'immatriculation_diplomatique',
                'autorisation_entree',
                'autorisation_sortie'
            ]);
            $table->foreignId('demandeur_user_id')->constrained('users')->onDelete('restrict');
            $table->enum('statut', [
                'brouillon',
                'soumis',
                'en_cours',
                'valide',
                'rejete',
                'expire',
                'annule',
                'cloture'
            ])->default('brouillon');
            $table->text('motif')->nullable();
            $table->date('date_depart_prevue')->nullable();
            $table->string('pays_destination')->nullable();
            $table->timestamp('date_soumission')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->timestamp('date_rejet')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->date('date_expiration')->nullable();
            $table->enum('priorite', ['normal', 'urgent'])->default('normal');
            $table->string('canal', 50)->default('interne');
            $table->timestamps();
            
            $table->index('reference');
            $table->index('statut');
            $table->index('type_demande');
            $table->index('demandeur_user_id');
            $table->index('date_soumission');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
