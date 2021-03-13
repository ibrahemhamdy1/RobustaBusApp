<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
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
     * Setup the relationship for from station.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromStation()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Setup the from station relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toStation()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Setup the bus relationship.
     *
     * @return void
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
