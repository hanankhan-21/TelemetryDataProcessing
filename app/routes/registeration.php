<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get(
    '/registerUser',
    function (Request $request, Response $response) use ($app) {

        $container = $app->getContainer();
        $registerController = $container->get('registerationController');

        return $registerController->outputRegisterPage(
            $container,
            $response
        );
    }
);

$app->post(
    '/registerUser',
    function (Request $request, Response $response) use ($app) {

        $container = $app->getContainer();
        $registerController = $container->get('registerationController');

        return $registerController->registerUser(
            $container,
            $request,
            $response
        );
    }
);

// SUCCESS PAGE
$app->get('/registerSuccess', function(Request $req, Response $res) use ($app) {

    $container = $app->getContainer();
    $settings  = $container->get('settings');

    // FIX: Add basePath correctly
    $base = rtrim($settings['landing_page'], '/'); // /telemetryDataProcessing2/public

    return $container->get('view')->render($res, 'register_success.html.twig', [
        'dashboard_url' => $base . '/dashboard'
    ]);
});


