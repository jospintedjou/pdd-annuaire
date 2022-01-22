<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('categorie_activite');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->time('heure_debut');
            $table->string('lieu');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('categorie_activite')->references('id')->on('categorie_activites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activites');
    }
}
