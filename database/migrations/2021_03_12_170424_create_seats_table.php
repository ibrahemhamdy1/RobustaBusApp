<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('number');

            $table->foreignId('bus_id')->nullable();
            $table->foreign('bus_id')->references('id')->on('buses');

            $table->foreignId('from_station_id')->nullable();
            $table->foreign('from_station_id')->references('id')->on('stations');

            $table->foreignId('to_station_id')->nullable();
            $table->foreign('to_station_id')->references('id')->on('stations');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
