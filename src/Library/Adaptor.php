<?php

namespace Flights\Library;

use Amadeus\Amadeus;
use Amadeus\Exceptions\ResponseException;

class Adaptor
{
    public function searchFlights($data)
    {
        $amadeus = Amadeus::builder(CLIENT_ID, CLIENT_SECRET)->build();

        $flightOffers = $amadeus->getShopping()->getFlightOffers()->post($data);

        dd($flightOffers[0]);
    }
}