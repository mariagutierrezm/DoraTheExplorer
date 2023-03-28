<?php

include "../vendor/autoload.php";
include "../env.php";
// disable deprecated warnings
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

$settings = new \Flights\Settings\Settings();
$dependencies = new \Flights\Settings\Dependencies();

$app = (new \Slim\App($settings->getSettings()));

$dependencies->setDependencies($app);
$dependencies->setControllers($app);

// Register the routes
(new \Flights\Routes\SearchRoutes($app));

$app->run();
