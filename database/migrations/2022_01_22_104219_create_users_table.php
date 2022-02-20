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
            $table->date('date_naissance')->nullable();
            $table->string('etat')->nullable();
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->string('pays');
            $table->string('ville');
            $table->string('quartier')->nullable();
            $table->unsignedBigInteger('niveau_engagement_id')->nullable();
            $table->string('role');
            $table->string('categorie_sociale');
            $table->unsignedBigInteger('apostolat_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->softDeletes();
            $table->foreign('apostolat_id')->references('id')->on('apostolats');
            $table->foreign('niveau_engagement_id')->references('id')->on('niveau_engagements');
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
