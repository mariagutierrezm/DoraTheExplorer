<?php

namespace Flights\Library;

class FlightDataFactory
{

    /**
     * Uses the array passed to create a structured array with flight search information
     */
    public function createFlightDataFromArray(array $data): array
    {
        // Create the base object
        $obj = [
            "currencyCode" => $data["currencyCode"],
            "originDestinations" => [
                [
                    "id" => "1",
                    "originLocationCode" => $data["originLocationCode"],
                    "destinationLocationCode" => $data["destinationLocationCode"],
                    "departureDateTimeRange" => [
                        "date" => $data["departureDate"]
                    ]
                ],
                [
                    "id" => "2",
                    "originLocationCode" => $data["destinationLocationCode"],
                    "destinationLocationCode" => $data["originLocationCode"],
                    "departureDateTimeRange" => [
                        "date" => $data["returnDate"]
                    ]
                ]
            ],
            "sources" => ["GDS"],
            "searchCriteria" => [
                "maxFlightOffers" => intval($data["max"])
            ]
        ];

        // Create the travelers array
        $travelers = [];
        for ($i = 1; $i <= intval($data["adults"]); $i++) {
            $traveler = [
                "id" => strval($i),
                "travelerType" => "ADULT"
            ];
            array_push($travelers, $traveler);
        }
        $obj["travelers"] = $travelers;

        return $obj;
    }
}