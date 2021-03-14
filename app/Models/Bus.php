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
     * @param  int  $formStationId
     * @param  int  $toSatiationId
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function reservedSeatsInBus(int $formStationId, int $toSatiationId)
    {
        return $this->seats()->where('from_station_id', $formStationId)
            ->where('to_station_id', $toSatiationId)->get();
    }

    /**
     * Get available seats for a specific trip.
     *
     * @param  int  $formStationId
     * @param  int  $toSatiationId
     *
     * @return int
     */
    public function numberOfAvailableSeats(int $formStationId, int $toSatiationId)
    {
        return $this->available_seats - $this->reservedSeatsInBus($formStationId, $toSatiationId)->count();
    }

    /**
     * Generate a random number for available seats.
     *
     * @param  int  $formStationId
     * @param  int  $toSatiationId
     *
     * @return int
     */
    public function randomSeatNumberOfAvailableSeats(int $formStationId, int $toSatiationId)
    {
        return array_rand($this->availableSeatsNumbers($formStationId, $toSatiationId), 1);
    }

    /**
     * Available seats numbers.
     *
     * @param  int  $formStationId
     * @param  int  $toSatiationId
     *
     * @return array
     */
    public function availableSeatsNumbers(int $formStationId, int $toSatiationId)
    {
        return array_diff(range(1, $this->available_seats), $this->reservedSeatsInBus($formStationId, $toSatiationId)->pluck('number')->toArray());
    }
}
