<?php




use DI\Container;
use Slim\Factory\AppFactory;
use Helpers\Database;
use Models\RegisterationModelModel;
use Models\LoginModel;

require __DIR__ . '/vendor/autoload.php';

$baseDir = __DIR__;
$appDir = $baseDir . '/app';
$configDir = $appDir . '/config';
$routesDir = $appDir . '/routes';

$container = new Container();
AppFactory::setContainer($container);


$app = AppFactory::create();
$app->setBasePath(basePath: '/telemetryDataProcessing1/public');


$settings  = require $configDir . '/settings.php';
$settings($container, $appDir);

$dependencies = require $configDir . '/dependencies.php';
$dependencies($container, $app);

$middleware = require $configDir . '/middleware.php';
$middleware($app);

$routes = require $routesDir . '/routes.php';

$db = $container->get(Database::class);
$db->connectToDatabase();

$app->run();

?>