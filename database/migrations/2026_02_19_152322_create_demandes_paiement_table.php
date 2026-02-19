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
            Schema::create('demandes_paiement', function (Blueprint $table) {
                $table->id();
                $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
                $table->decimal('montant', 10, 2);
                $table->string('transaction_id')->nullable(); // numéro Wave
                $table->enum('statut', ['en_attente', 'soumis', 'valide', 'rejete'])->default('en_attente');
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_paiement');
    }
};
