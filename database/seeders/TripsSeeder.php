<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\Station;
use Illuminate\Database\Seeder;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StationsSeeder::class);

        $stationIds = Station::select('id')->get()->pluck('id')->toArray();
        $trip = Trip::updateOrCreate([
            'start_date_and_time' => '2021-03-14 09:20:20',
            'end_date_and_time' => now()->addHours(3),
        ]);

        $trip->stations()->sync($stationIds);
        $trip->bus()->associate(Bus::inRandomOrder()->first())->save();
    }
}
