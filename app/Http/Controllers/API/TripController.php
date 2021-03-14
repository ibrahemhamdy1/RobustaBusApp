<?php

namespace App\Http\Controllers\API;

use App\Models\Seat;
use App\Models\Trip;
use App\Models\Station;
use Illuminate\Http\Request;

class TripController extends BaseController
{
    /**
     * Check if the there is available trip.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $formDate = $request->input('from_date');
        $fromStationInput = $request->input('from_station');
        $toStationInput = $request->input('to_station');
        $formStationId = optional(Station::where('name', $fromStationInput)->first())->id;
        $toStationId = optional(Station::where('name', $toStationInput)->first())->id;

        $trip = Trip::where('start_date_and_time', $formDate)->first();

        $bus = $trip->bus ?? null;

        //If there is available seats show him that seat
        if (isset($bus, $formStationId, $toStationId) && $bus->numberOfAvailableSeats($formStationId, $toStationId) > 0) {
            // Get the available seat number;
            $availableSeatsNumbers = $bus->availableSeatsNumbers($formStationId, $toStationId);

            if (!empty($availableSeatsNumbers)) {
                return $this->sendResponse(
                    ['available_seat_numbers' => $availableSeatsNumbers],
                    "There is available seats form $fromStationInput station to $toStationInput station"
                );
            } else {
                return $this->sendError('', 'We do not have seats numbers');
            }
        }

        return $this->sendError('', "There is no available seats form $fromStationInput station to $toStationInput station");
    }

    /**
     * Reserve available trip.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formDate = $request->input('from_date');
        $fromStationInput = $request->input('from_station');
        $toStationInput = $request->input('to_station');
        $formStationId = optional(Station::where('name', $fromStationInput)->first())->id;
        $toStationId = optional(Station::where('name', $toStationInput)->first())->id;

        $trip = Trip::where('start_date_and_time', $formDate)->first();

        $bus = $trip->bus ?? null;

        //If there is available seats show him that seat
        if (isset($bus, $formStationId, $toStationId) && $bus->numberOfAvailableSeats($formStationId, $toStationId) > 0) {
            // Get the available seat number;
            $availableSeatsNumbers = $bus->availableSeatsNumbers($formStationId, $toStationId);

            if (!empty($availableSeatsNumbers)) {
                $seatNumber = $trip->bus->randomSeatNumberOfAvailableSeats($formStationId, $toStationId);

                $seat = Seat::create([
                    'number' => $seatNumber,
                ]);

                $seat->bus()->associate($bus)->save();
                $seat->fromStation()->associate($formStationId)->save();
                $seat->toStation()->associate($toStationId)->save();
            }

            return $this->sendResponse(
                    [
                        'trip_id' => $trip->id,
                        'trip_start_date_and_time' => $trip->start_date_and_time,
                        'trip_end_date_and_time' => $trip->end_date_and_time,
                        'seat_number' => $seat->number,
                        'bus_number' => $bus->number,
                    ],
                    "You just reserved a seat from $fromStationInput station to $toStationInput station"
                );
        } else {
            return $this->sendError('', 'We do not have seats available for this trip');
        }

        return $this->sendError('', "There is no available seats form $fromStationInput station to $toStationInput station");
    }
}
