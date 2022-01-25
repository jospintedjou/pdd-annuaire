<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApostolatConcernesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apostolat_concernes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('categorie_activite_id');
            $table->unsignedInteger('apostolat_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('categorie_activite_id')->references('id')->on('categorie_activites');
            $table->foreign('apostolat_id')->references('id')->on('apostolats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apostolat_concernes');
    }
}
