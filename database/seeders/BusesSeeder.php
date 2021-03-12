<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $busesNumbers = [
            '1',
            '2',
        ];

        foreach ($busesNumbers as $busesNumber) {
            Bus::updateOrCreate(['number' => $busesNumber]);
        }
    }
}
