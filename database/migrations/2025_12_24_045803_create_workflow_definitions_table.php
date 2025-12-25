<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('libelle');
            $table->boolean('actif')->default(true);
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            
            $table->index('code');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_definitions');
    }
};
