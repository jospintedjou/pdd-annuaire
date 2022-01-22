<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorie_activites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sous_zone')->nullable();
            $table->unsignedInteger('groupe')->nullable();
            $table->string('nom');
            $table->string('periodicite');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('groupe')->references('id')->on('groupes');
            $table->foreign('sous_zone')->references('id')->on('sous_zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorie_activites');
    }
}
