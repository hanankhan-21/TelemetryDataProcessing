<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/userInfo', function (Request $request, Response $response) use ($app) {

    $container     = $app->getContainer();
    $userInfoCtrl  = $container->get('userInfoController');

    return $userInfoCtrl->showUserInfo($container, $request, $response);

})->add($authMiddleware);
