<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/logout', function (Request $request, Response $response) use ($app) {

    $container    = $app->getContainer();
    $settings     = $container->get('settings');
    $landing_page = rtrim($settings['landing_page'], '/');

    // Ensure session is started before destroying
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Clear all session data
    $_SESSION = [];

    // Also clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy session
    session_destroy();

    // Redirect to login page (landing page "/")
    return $response
        ->withHeader('Location', $landing_page . '/')
        ->withStatus(302);
});
