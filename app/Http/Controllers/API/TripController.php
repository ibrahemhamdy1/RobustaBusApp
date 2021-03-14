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
        $tripInfo = $this->getTripInfo($request);

        $bus = $tripInfo['bus'];

        $fromStationName = $tripInfo['from_station']->name;
        $toStationName = $tripInfo['to_station']->name;
        $fromStationId = $tripInfo['from_station']->id;
        $toStationId = $tripInfo['to_station']->id;

        //If there is available seats show him that seat
        if (isset($bus, $fromStationId, $toStationId) && $bus->numberOfAvailableSeats($fromStationId, $toStationId) > 0) {
            // Get the available seat number;
            $availableSeatsNumbers = $bus->availableSeatsNumbers($fromStationId, $toStationId);

            if (!empty($availableSeatsNumbers)) {
                return $this->sendResponse(
                    ['available_seat_numbers' => $availableSeatsNumbers],
                    "There is available seats form $fromStationName station to $toStationName station"
                );
            } else {
                return $this->sendError('', 'We do not have seats numbers');
            }
        }

        return $this->sendError('', "There is no available seats form $fromStationName station to $toStationName station");
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
        $tripInfo = $this->getTripInfo($request);

        $bus = $tripInfo['bus'];

        $fromStationName = $tripInfo['from_station']->name;
        $toStationName = $tripInfo['to_station']->name;
        $fromStationId = $tripInfo['from_station']->id;
        $toStationId = $tripInfo['to_station']->id;
        $trip = $tripInfo['trip'];

        if (isset($bus, $fromStationId, $toStationId) && $bus->numberOfAvailableSeats($fromStationId, $toStationId) > 0) {
            $availableSeatsNumbers = $bus->availableSeatsNumbers($fromStationId, $toStationId);

            if (!empty($availableSeatsNumbers)) {
                $seatNumber = $bus->randomSeatNumberOfAvailableSeats($fromStationId, $toStationId);
                $seat = Seat::create([
                    'number' => $seatNumber,
                ]);

                $seat->bus()->associate($bus);
                $seat->fromStation()->associate($fromStationId);
                $seat->toStation()->associate($toStationId);
                $seat->save();

                return $this->sendResponse(
                    [
                        'trip_id' => $trip->id,
                        'trip_start_date_and_time' => $trip->start_date_and_time,
                        'trip_end_date_and_time' => $trip->end_date_and_time,
                        'seat_number' => $seat->number,
                        'bus_number' => $bus->number,
                    ],
                    "You just reserved a seat from $fromStationName station to $toStationName station"
                );
            }
        } else {
            return $this->sendError('', 'We do not have seats available for this trip');
        }

        return $this->sendError('', "There is no available seats form $fromStationName station to $toStationName station");
    }

    public function getTripInfo(Request $request)
    {
        $formDate = $request->input('from_date');
        $fromStationInput = $request->input('from_station');
        $toStationInput = $request->input('to_station');
        $fromStation = Station::where('name', $fromStationInput)->first();
        $toStation = Station::where('name', $toStationInput)->first();

        $trip = Trip::where('start_date_and_time', $formDate)->first();

        $bus = $trip->bus ?? null;

        return [
            'trip' => $trip,
            'bus' => $bus,
            'from_station' => $fromStation,
            'to_station' => $toStation,
        ];
    }
}
