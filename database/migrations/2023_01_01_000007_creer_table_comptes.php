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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->enum('type', ['actif', 'passif', 'produit', 'charge']);
            $table->decimal('solde', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
