<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;



$app->get('/logout', function ($request, $response) use ($app) {
    $controller = $app->getContainer()->get('logoutController');
    return $controller->logout($app->getContainer(), $request, $response);
});



?>