<?php

namespace Flights\Controller;

use Slim\Container;
use \Flights\Library\FlightsClient;
use \Flights\Library\AmadeusAdaptor;
use \Flights\Library\FlightDataFactory;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class SearchController
{
    private Container $container;
    private AmadeusAdaptor $amadeusAdaptor;
    private FlightsClient $flightsClient;
    private FlightDataFactory $flightDataFactory;
    
    public function __construct(Container $container)
    {
      $this->container = $container;
      $this->amadeusAdaptor = new AmadeusAdaptor();
      $this->flightsClient = new FlightsClient();
      $this->flightDataFactory = new FlightDataFactory();
    }

    /**
     * Renders the search page
     */
    public function view(Request $request, Response $response): Response
    {
      try {
      
        return $this->container->twig->render($response, 'search-form.html.twig');
      } catch (\Exception $e) {

        return $response->withStatus(400)->write('There was an error processing the request.');
      }

    }
    /**
     * It gets the search flight offers for a one way trip through a curl request
     */
    public function searchFlights(Request $request, Response $response): Response
    {
      try {
        $data = $request->getQueryParams();
        
        $result = $this->flightsClient->searchFlightsCURL($data);

        return $response->withStatus(200)->withJson($result);

      } catch (\Exception $e) {

        return $response->withStatus(400)
            ->withHeader('Content-type', "application/json")
            ->withJson([
                'success' => 0,
                'message' => 'There was an error processing the request.'
            ]);
      }
    }
    /**
     * It gets flight offers quering the api via the Amadeus SDK methods
     */
    public function searchFlightsAmadeus(Request $request, Response $response): Response
    {
    try {
        $bodyData = $request->getParsedBody();
        
        $data = $this->flightDataFactory->createFlightDataFromArray($bodyData);
        
        $result = $this->amadeusAdaptor->searchFlightsSDK($data);

        return $response->withStatus(200)->withJson($result);

    } catch (\Exception $e) {

        return $response->withStatus(400)
            ->withHeader('Content-type', "application/json")
            ->withJson([
                'success' => 0,
                'message' => 'There was an error processing the request.'
            ]);
    }
  }
}
