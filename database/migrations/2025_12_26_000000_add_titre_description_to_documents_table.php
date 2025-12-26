<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('titre', 255)->after('type_document')->default('')->comment('Titre de la pièce jointe');
            $table->text('description')->after('titre')->nullable()->comment('Description détaillée de la pièce');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['titre', 'description']);
        });
    }
};
