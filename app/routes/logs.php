<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/logs', function (Request $request, Response $response) use ($app) {
    $container     = $app->getContainer();
    $logsController = $container->get('logsController');

    return $logsController->showLogsPage($container, $request, $response);
})->add($authMiddleware);
