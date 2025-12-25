<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->unique()->constrained('demandes')->onDelete('cascade');
            $table->foreignId('workflow_definition_id')->constrained('workflow_definitions')->onDelete('restrict');
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            $table->index('demande_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_instances');
    }
};
