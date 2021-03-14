<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
    ];

    /**
     * Setup the seats relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Get all the for a specific trip.
     *
     * @param  int  $fromStationId
     * @param  int  $toSatiationId
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function reservedSeatsInBus(int $fromStationId, int $toSatiationId)
    {
        return $this->seats()->where('from_station_id', $fromStationId)
            ->where('to_station_id', $toSatiationId)->get();
    }

    /**
     * Get available seats for a specific trip.
     *
     * @param  int  $fromStationId
     * @param  int  $toSatiationId
     *
     * @return int
     */
    public function numberOfAvailableSeats(int $fromStationId, int $toSatiationId)
    {
        return $this->available_seats - $this->reservedSeatsInBus($fromStationId, $toSatiationId)->count();
    }

    /**
     * Generate a random number for available seats.
     *
     * @param  int  $fromStationId
     * @param  int  $toSatiationId
     *
     * @return int
     */
    public function randomSeatNumberOfAvailableSeats(int $fromStationId, int $toSatiationId)
    {
        $availableSeatsNumbers = $this->availableSeatsNumbers($fromStationId, $toSatiationId);
        $key = array_rand($availableSeatsNumbers, 1);

        return  $availableSeatsNumbers[$key];
    }

    /**
     * Available seats numbers.
     *
     * @param  int  $fromStationId
     * @param  int  $toSatiationId
     *
     * @return array
     */
    public function availableSeatsNumbers(int $fromStationId, int $toSatiationId)
    {
        return array_diff(range(1, $this->available_seats), $this->reservedSeatsInBus($fromStationId, $toSatiationId)->pluck('number')->toArray());
    }
}
