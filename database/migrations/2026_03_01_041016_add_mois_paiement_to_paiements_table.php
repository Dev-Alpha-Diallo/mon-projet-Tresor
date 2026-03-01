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
    Schema::table('paiements', function (Blueprint $table) {
        // Mois concerné par le paiement (ex: 2026-02-01 = février 2026)
        $table->date('mois_paiement')->nullable()->after('date_paiement');
    });
}

public function down(): void
{
    Schema::table('paiements', function (Blueprint $table) {
        $table->dropColumn('mois_paiement');
    });
}
};
