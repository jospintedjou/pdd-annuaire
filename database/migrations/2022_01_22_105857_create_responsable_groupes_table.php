<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsableGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsable_groupes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('groupe');
            $table->unsignedInteger('user');
            $table->string('nom_responsabilite');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('groupe')->references('id')->on('groupes');
            $table->foreign('user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responsable_groupes');
    }
}
