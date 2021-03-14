<?php

namespace App\Http\Controllers\API;

use App\Models\Trip;
use App\Models\Station;
use Illuminate\Http\Request;

class TripController extends BaseController
{
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

        return $this->sendError('', "There is no available seats form $fromStationInput station to $toStationInput station");
    }
}
