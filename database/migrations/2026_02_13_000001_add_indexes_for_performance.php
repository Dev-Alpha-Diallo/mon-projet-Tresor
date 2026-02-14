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
        Schema::table('etudiants', function (Blueprint $table) {
            if (!Schema::hasColumn('etudiants', 'maison_id')) return;
            $table->index('maison_id');
            if (Schema::hasColumn('etudiants', 'chambre')) {
                $table->index('chambre');
            }
        });

        Schema::table('paiements', function (Blueprint $table) {
            if (Schema::hasColumn('paiements', 'date_paiement')) {
                $table->index('date_paiement');
            }
        });

        Schema::table('factures', function (Blueprint $table) {
            if (Schema::hasColumn('factures', 'date_paiement')) {
                $table->index('date_paiement');
            }
            if (Schema::hasColumn('factures', 'statut')) {
                $table->index('statut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            if (Schema::hasColumn('etudiants', 'maison_id')) {
                $table->dropIndex(['maison_id']);
            }
            if (Schema::hasColumn('etudiants', 'chambre')) {
                $table->dropIndex(['chambre']);
            }
        });

        Schema::table('paiements', function (Blueprint $table) {
            if (Schema::hasColumn('paiements', 'date_paiement')) {
                $table->dropIndex(['date_paiement']);
            }
        });

        Schema::table('factures', function (Blueprint $table) {
            if (Schema::hasColumn('factures', 'date_paiement')) {
                $table->dropIndex(['date_paiement']);
            }
            if (Schema::hasColumn('factures', 'statut')) {
                $table->dropIndex(['statut']);
            }
        });
    }
};
