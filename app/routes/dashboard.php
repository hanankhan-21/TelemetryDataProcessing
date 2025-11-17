<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// DASHBOARD PAGE
$app->get('/dashboard', function (Request $request, Response $response) use ($app) {

    $container = $app->getContainer();
    $dashboardController = $container->get('dashboardController');

    return $dashboardController->outputDashboardPage(
        $container,
        $request,
        $response
    );
});
