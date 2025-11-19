<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/deviceInfo', function (Request $request, Response $response) use ($app) {
    $container = $app->getContainer();
    $ctrl      = $container->get('deviceInfoController');

    return $ctrl->showDeviceInfo($container, $request, $response);
});
