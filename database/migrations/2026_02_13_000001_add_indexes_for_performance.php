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
            // Index etudiants
            Schema::table('etudiants', function (Blueprint $table) {
                $table->index('maison_id');
                $table->index('user_id');   // ← important pour le login client
            });

            // Index paiements
            Schema::table('paiements', function (Blueprint $table) {
                $table->index('etudiant_id'); // ← le plus important !
                $table->index('date_paiement');
            });

            // Index demandes_paiement
            Schema::table('demandes_paiement', function (Blueprint $table) {
                $table->index('etudiant_id');
                $table->index('statut');
            });

            // Index factures
            Schema::table('factures', function (Blueprint $table) {
                if (Schema::hasColumn('factures', 'statut')) {
                    $table->index('statut');
                }
            });
        }

        public function down(): void
        {
            Schema::table('etudiants', function (Blueprint $table) {
                $table->dropIndex(['maison_id']);
                $table->dropIndex(['user_id']);
            });

            Schema::table('paiements', function (Blueprint $table) {
                $table->dropIndex(['etudiant_id']);
                $table->dropIndex(['date_paiement']);
            });

            Schema::table('demandes_paiement', function (Blueprint $table) {
                $table->dropIndex(['etudiant_id']);
                $table->dropIndex(['statut']);
            });

            Schema::table('factures', function (Blueprint $table) {
                if (Schema::hasColumn('factures', 'statut')) {
                    $table->dropIndex(['statut']);
                }
            });
        }
};
