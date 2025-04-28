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
        Schema::create('lignes_facture', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('taux_tva', 5, 2);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            $table->foreignId('facture_id')->constrained('factures')->onDelete('cascade');
            $table->foreignId('produit_id')->nullable()->constrained('produits');
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes_facture');
    }
};
