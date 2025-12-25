<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            $table->string('action', 100);
            $table->foreignId('auteur_id')->constrained('users')->onDelete('restrict');
            $table->text('commentaire')->nullable();
            $table->timestamp('created_at');
            
            $table->index('demande_id');
            $table->index('auteur_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_demandes');
    }
};
