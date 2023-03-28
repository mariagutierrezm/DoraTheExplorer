<?php

namespace Flights\App\Routes;

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
        $this->app->get('/search', 'SearchController:test');
    }
}