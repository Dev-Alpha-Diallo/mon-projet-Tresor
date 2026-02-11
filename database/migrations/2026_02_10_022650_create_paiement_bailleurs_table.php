<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_bailleurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bailleur_id')->constrained('bailleurs')->onDelete('cascade');
            $table->foreignId('maison_id')->constrained('maisons')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->text('remarque')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_bailleurs');
    }
};