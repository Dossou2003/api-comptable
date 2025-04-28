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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('description');
            $table->foreignId('compte_debit_id')->constrained('comptes');
            $table->foreignId('compte_credit_id')->constrained('comptes');
            $table->decimal('montant', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
