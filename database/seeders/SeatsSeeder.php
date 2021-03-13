<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class SeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StationsSeeder::class);
        $this->call(BusesSeeder::class);
        $this->call(TripsSeeder::class);

        $trip = Trip::first();
        $stations = $trip->stations()->orderBy('id')->limit(2)->get()->pluck('id')->toArray();
        $bus = $trip->bus;
        $busSeats = $bus->seats;

        $availableSeatsInBus = $bus->availableSeats($stations[0], $stations[1]);
        if ($availableSeatsInBus > 0) {
            while (in_array(($seatNumber = rand(1, $bus->available_seats)), $busSeats->pluck('number')->toArray()));

            Seat::create([
                'number' => $seatNumber,
                'bus_id' => $bus->id,
                'from_station_id' => $stations[0],
                'to_station_id' => $stations[1],
            ]);
        }
    }
}
