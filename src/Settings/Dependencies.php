<?php

namespace Flights\Settings;

use Slim\App;
use Slim\Container;
use \Flights\Controller\SearchController;

class Dependencies
{

    public function setDependencies(App $app): void
    {
        $container = $app->getContainer();

        $container['twig'] = function ($container) {
        
            $settings = $container->get('settings');

            $view = new \Slim\Views\Twig($settings['templates.path'], $settings['twig']);
    
            $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $container['request']->getUri()));
            return $view;
        };
    }

    public function setControllers(App $app): void
    {
        $container = $app->getContainer();

        $container['SearchController'] = function (Container $container) {
            return new SearchController($container);
        };
    }
}