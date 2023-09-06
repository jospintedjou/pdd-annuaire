<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnNomResponsabiliteInResponsableGroupeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsable_groupe', function (Blueprint $table) {
            $table->dropColumn('nom_responsabilite');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsable_groupe', function (Blueprint $table) {
            //
        });
    }
}
