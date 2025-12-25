<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_beneficiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            $table->enum('beneficiaire_type', ['fonctionnaire', 'ayant_droit']);
            $table->unsignedBigInteger('beneficiaire_id');
            $table->enum('role_dans_demande', ['principal', 'secondaire'])->default('principal');
            $table->text('commentaire')->nullable();
            $table->timestamps();
            
            $table->index('demande_id');
            $table->index(['beneficiaire_type', 'beneficiaire_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_beneficiaires');
    }
};
