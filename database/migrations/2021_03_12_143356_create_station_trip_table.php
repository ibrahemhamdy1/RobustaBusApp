<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_trip', function (Blueprint $table) {
            $table->id();

            $table->foreignId('station_id');
            $table->foreign('station_id')->references('id')->on('stations');

            $table->foreignId('trip_id');
            $table->foreign('trip_id')->references('id')->on('trips');

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
        Schema::dropIfExists('station_trip');
    }
}
