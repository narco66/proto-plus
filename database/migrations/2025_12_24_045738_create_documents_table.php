<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            $table->enum('beneficiaire_type', ['fonctionnaire', 'ayant_droit'])->nullable();
            $table->unsignedBigInteger('beneficiaire_id')->nullable();
            $table->string('type_document', 100);
            $table->string('nom_fichier');
            $table->string('chemin_fichier', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('taille')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->boolean('confidentiel')->default(false);
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('demande_id');
            $table->index('created_by');
            $table->index('confidentiel');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
