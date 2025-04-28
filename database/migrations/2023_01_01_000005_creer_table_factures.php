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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->enum('statut', ['brouillon', 'envoyée', 'payée', 'annulée'])->default('brouillon');
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('taux_tva', 5, 2);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            $table->text('notes')->nullable();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('createur_id')->constrained('utilisateurs');
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
