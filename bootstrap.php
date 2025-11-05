<?php

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/VENDOR/autoload.php';

$baseDir   = __DIR__;
$appDir    = $baseDir . '/app';
$configDir = $appDir . '/config';
$routesDir = $appDir . '/routes';


$container = new Container();
AppFactory::setContainer($container);


$app = AppFactory::create();

$settings     = require $configDir . '/settings.php';
$dependencies = require $configDir . '/dependencies.php';
$middleware   = require $configDir . '/middleware.php';
$routes       = require $routesDir . '/routes.php';

$settings($container, $appDir);      
$dependencies($container, $app);     
$middleware($app);                  
$routes($app);

$app->run();