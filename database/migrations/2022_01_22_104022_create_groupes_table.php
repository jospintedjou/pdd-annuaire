<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sous_zone');
            $table->string('nom_groupe');
            $table->string('paroisse');
            $table->string('jour_reunion');
            $table->time('heure_reunion');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('groupes');
    }
}
