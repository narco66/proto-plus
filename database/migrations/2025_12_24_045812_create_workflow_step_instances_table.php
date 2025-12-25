<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_step_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('workflow_instances')->onDelete('cascade');
            $table->foreignId('step_definition_id')->constrained('workflow_step_definitions')->onDelete('restrict');
            $table->enum('statut', [
                'a_faire',
                'en_traitement',
                'valide',
                'rejete',
                'retour_correction',
                'skipped'
            ])->default('a_faire');
            $table->string('assigned_role', 100)->nullable();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('decided_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('decision_at')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
            
            $table->index('workflow_instance_id');
            $table->index('statut');
            $table->index('assigned_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_step_instances');
    }
};
