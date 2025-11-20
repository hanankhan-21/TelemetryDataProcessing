<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/aboutUs', function (Request $request, Response $response) use ($app) {
    $container       = $app->getContainer();
    $aboutUsController = $container->get('aboutUsController');

    return $aboutUsController->outputAboutUsPage($container, $request, $response);
})->add($authMiddleware); // remove add() if you want it public
