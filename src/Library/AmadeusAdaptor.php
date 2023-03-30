<?php

namespace Flights\Library;

use Amadeus\Amadeus;

class AmadeusAdaptor
{
    /**
     * Builds an instance of Amadeus class and then uses its built in function to do a post request
     * to the Api to get the flight offers matching the data passed
     * It returns the matched offers
     */
    public function searchFlightsSDK(array $data): object
    {
        $amadeus = Amadeus::builder(CLIENT_ID, CLIENT_SECRET)->build();

        $flightOffers = $amadeus->getShopping()->getFlightOffers()->post(json_encode($data));

        return $flightOffers[0]->getResponse()->getBodyAsJsonObject();
    }
   
}