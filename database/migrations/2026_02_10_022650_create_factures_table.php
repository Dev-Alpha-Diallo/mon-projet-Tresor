<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // php artisan make:migration create_factures_table
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maison_id')->constrained('maisons')->onDelete('cascade');
            $table->string('numero_facture')->unique();
            $table->enum('type', ['eau', 'electricite', 'reparation', 'autre']);
            $table->decimal('montant', 10, 2);
            $table->date('date_emission');           // quand la facture a été émise
            $table->date('date_echeance');           // deadline de paiement
            $table->date('date_paiement')->nullable(); // rempli quand payée
            $table->enum('statut', ['impayee', 'partiel', 'payee'])->default('impayee');
            $table->text('description')->nullable();
            $table->text('remarques')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};