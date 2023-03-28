<?php

namespace Flights\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Flights\Library\Adaptor;

class SearchController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function displaySearchForm(Request $request, Response $response): Response
    {
        return $this->container->twig->render($response, 'search-form.html.twig');
    }

    public function searchFlights(Request $request, Response $response): Response
    {
        $data ='{
            "originDestinations": [
              {
                "id": "1",
                "originLocationCode": "PAR",
                "destinationLocationCode": "MAD",
                "departureDateTimeRange": {
                  "date": "2023-08-29"
                }
              }
            ],
            "travelers": [
              {
                "id": "1",
                "travelerType": "ADULT"
              }
            ],
            "sources": [
              "GDS"
            ]
          }';

        $library = new Adaptor();
        $result = $library->searchFlights($data);
        
        return $response->withStatus(200)->withJson($result);
    }
}