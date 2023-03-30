<?php

namespace Flights\Routes;

/**
 * Search Routes
 */
class SearchRoutes
{
    /**
     * Slim instance
     */
    protected $app;
    /**
     * Array of Slim route groups
     *
     * @var \Slim\Routeable
     */
    protected $routes;

    public function __construct($app)
    {
        $this->app = $app;
        $this->routes();
    }

    public function routes()
    {
        $this->app->get('/search', 'SearchController:view');
        $this->app->get('/search-flight', 'SearchController:searchFlights');
        $this->app->post('/search-flight', 'SearchController:searchFlightsAmadeus');
    }
}