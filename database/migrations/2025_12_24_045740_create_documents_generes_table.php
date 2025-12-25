<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents_generes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            $table->enum('type_modele', ['note_verbale', 'lettre_immigration', 'autre']);
            $table->string('numero', 100);
            $table->string('fichier_path', 500);
            $table->boolean('signe')->default(false);
            $table->timestamp('date_generation');
            $table->foreignId('generated_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('demande_id');
            $table->index('type_modele');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents_generes');
    }
};
