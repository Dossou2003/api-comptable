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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('taux_tva', 5, 2);
            $table->foreignId('categorie_id')->constrained('categories');
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
