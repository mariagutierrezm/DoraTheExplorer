<?php

include "../vendor/autoload.php";
// disable deprecated warnings
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

$settings = new \Flights\App\Settings\Settings();
$dependencies = new \Flights\App\Settings\Dependencies();

$app = (new \Slim\App($settings->getSettings()));

$dependencies->setDependencies($app);
$dependencies->setControllers($app);

// Register the routes
(new \Flights\App\Routes\SearchRoutes($app));

$app->run();
