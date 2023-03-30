<?php

namespace Flights\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Flights\Library\AmadeusAdaptor;
use \Flights\Library\FlightsClient;

class SearchController
{
    protected $container;
    protected $amadeusAdaptor;
    protected $flightsClient;

    public function __construct($container)
    {
      $this->container = $container;
      $this->amadeusAdaptor = new AmadeusAdaptor();
      $this->flightsClient = new FlightsClient();
    }

    public function displaySearchForm(Request $request, Response $response): Response
    {
        return $this->container->twig->render($response, 'search-form.html.twig');
    }

    public function searchFlights(Request $request, Response $response): Response
    {
      try {
        $data = $request->getQueryParams();
        
        $result = $this->flightsClient->searchFlightsCURL($data);

        return $response->withStatus(200)->withJson($result);

      } catch (\Exception $e) {
        // syslog(LOG_INFO, "ERROR: ".$e->getMessage());

        return $response->withStatus(400)
            ->withHeader('Content-type', "application/json")
            ->withJson([
                'success' => 0,
                'message' => 'There was an error processing the request. Check the logs for more information.'
            ]);
      }
    }

    public function searchFlightsAmadeus(Request $request, Response $response): Response
    {
    try {
        $bodyData = $request->getParsedBody();

        $data = $this->amadeusAdaptor->formatFlightData($bodyData);

        $result = $this->amadeusAdaptor->searchFlightsSDK($data);

        return $response->withStatus(200)->withJson($result);
        
    } catch (\Exception $e) {

        return $response->withStatus(400)
            ->withHeader('Content-type', "application/json")
            ->withJson([
                'success' => 0,
                'message' => 'There was an error processing the request. Check the logs for more information.'
            ]);
    }
  }
}
