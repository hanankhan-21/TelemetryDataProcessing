<?php 

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogoutController{

public function logout($container, Request $request, Response $response)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        $settings = $container->get('settings');
        $landing_page = rtrim($settings['landing_page'], '/');

        return $response
            ->withHeader('Location', $landing_page)
            ->withStatus(302);
    }




}
