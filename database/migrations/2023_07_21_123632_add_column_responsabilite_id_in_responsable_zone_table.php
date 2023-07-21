<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnResponsabiliteIdInResponsableZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsable_zone', function (Blueprint $table) {
            $table->unsignedBigInteger('responsabilite_id')->nullable();
            $table->foreign('responsabilite_id')->references('id')->on('responsabilites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsable_zone', function (Blueprint $table) {
            //
        });
    }
}
