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
            $table->unsignedBigInteger('categorie_activite_id');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('sous_zone_id')->nullable();
            $table->unsignedBigInteger('groupe_id')->nullable();
            $table->enum('type', ['zonale','sous_zonale', 'groupe']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->time('heure_debut');
            $table->string('lieu');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('categorie_activite_id')->references('id')->on('categorie_activites');
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('sous_zone_id')->references('id')->on('sous_zones');
            $table->foreign('groupe_id')->references('id')->on('groupes');
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
