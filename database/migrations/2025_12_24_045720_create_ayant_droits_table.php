<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ayant_droits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fonctionnaire_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('civilite', ['M.', 'Mme', 'Mlle'])->default('M.');
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->enum('lien_familial', ['conjoint', 'enfant', 'autre'])->default('autre');
            $table->string('nationalite')->nullable();
            $table->string('passeport_num')->nullable();
            $table->date('passeport_expire_at')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
            
            $table->index('fonctionnaire_user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayant_droits');
    }
};
