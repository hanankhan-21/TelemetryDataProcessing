<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;

$app->get(
    '/',
    function(Request $request, Response $response)
    use ($app)
    {

        $container = $app->getContainer();

        $loginController= $container->get('loginController');

        $loginController->outputHtmlPage($container, $request, $response);

        return $response;
    }
)->setName('/');


$app->post(
    '/loginUser',
    function(Request $request, Response $response) use ($app) {

        $container       = $app->getContainer();
        $loginController = $container->get('loginController');

        // Just return what the controller returns
        return $loginController->loginUser($container, $request, $response);
    }
);
