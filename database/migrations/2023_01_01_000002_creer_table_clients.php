<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('societe')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable();
            $table->string('numero_tva')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('gestionnaire_id')->constrained('utilisateurs');
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
