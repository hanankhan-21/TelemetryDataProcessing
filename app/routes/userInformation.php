<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/userInfo', function (Request $request, Response $response) use ($app) {
    $container = $app->getContainer();
    $controller = $container->get('userInfoController');

    return $controller->showUserInfo($container, $request, $response);
});
