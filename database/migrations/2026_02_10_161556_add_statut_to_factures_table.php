<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('factures', function (Blueprint $table) {
        $table->enum('statut', ['payee', 'impayee'])
              ->default('payee')
              ->after('montant');
    });
}

public function down()
{
    Schema::table('factures', function (Blueprint $table) {
        $table->dropColumn('statut');
    });
}

};
