<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stationNames = [
            'Cairo',
            'AlFayyum',
            'AlMinya',
            'Asyut',
        ];

        foreach ($stationNames as $stationName) {
            Station::updateOrCreate(['name' => $stationName]);
        }
    }
}
