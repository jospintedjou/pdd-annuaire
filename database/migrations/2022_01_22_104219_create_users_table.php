<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('adresse');
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
            $table->enum('sexe', ['M','F']);
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->string('quartier');
            $table->unsignedInteger('niveau_engagement');
            $table->string('role');
            $table->string('categorie_sociale');
            $table->unsignedInteger('apostolat');
            $table->unsignedInteger('groupe');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('apostolat')->references('id')->on('apostolats');
            $table->foreign('groupe')->references('id')->on('groupes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
