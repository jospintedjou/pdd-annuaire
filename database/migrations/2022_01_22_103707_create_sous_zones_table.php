<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSousZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sous_zones', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('quartier');
            $table->unsignedInteger('zone');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('zone')->references('id')->on('zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sous_zones');
    }
}
