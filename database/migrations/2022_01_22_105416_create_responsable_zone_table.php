<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsableZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsable_zone', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nom_responsabilite');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responsable_zone');
    }
}
