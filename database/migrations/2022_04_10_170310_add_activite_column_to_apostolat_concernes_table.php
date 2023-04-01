<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiviteColumnToApostolatConcernesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apostolat_concernes', function (Blueprint $table) {
            $table->unsignedBigInteger('activite_id');
            $table->foreign('activite_id')->references('id')->on('activites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apostolat_concernes', function (Blueprint $table) {
            $table->dropColumn('activite_id');
        });
    }
}
