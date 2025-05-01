<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsablePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsable_pays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pays_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('responsabilite_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('pays_id')->references('id')->on('pays');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('responsable_pays');
    }
}
