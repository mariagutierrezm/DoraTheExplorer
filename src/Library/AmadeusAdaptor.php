<?php

namespace Flights\Library;

use Amadeus\Amadeus;
use Amadeus\Resources\FlightOffer;
use Amadeus\Exceptions\ResponseException;

class AmadeusAdaptor
{
    public function searchFlightsSDK($data)
    {
        $amadeus = Amadeus::builder(CLIENT_ID, CLIENT_SECRET)->build();

        $flightOffers = $amadeus->getShopping()->getFlightOffers()->post($data);

        return $flightOffers[0]->getResponse()->getBodyAsJsonObject();
    }
    
    public function formatFlightData(array $data)
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

        return json_encode($obj);
    }
}