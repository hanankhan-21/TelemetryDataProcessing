<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class UserInfoController{


 public function __construct() {}
    public function __destruct() {}

    public function showUserInfo($container, Request $request, Response $response): Response
    {
        $view          = $container->get('view');
        $settings      = $container->get('settings');
        $db            = $container->get('database');
        $userInfoModel = $container->get('userInfoModel');
        $userInfoView  = $container->get('userInfoView');

        // Ensure session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['email'] ?? null;

        if ($email === null) {
            $landing_page = rtrim($settings['landing_page'], '/');
            return $response
                ->withHeader('Location', $landing_page)
                ->withStatus(302);
        }

        // Fetch user data via model
        $user = $userInfoModel->getUserInfo($email, $db);

        if ($user === false || empty($user)) {
            // If somehow user not found, you can handle gracefully
            // For now: redirect to dashboard or show simple error
            $landing_page = rtrim($settings['landing_page'], '/');
            // Optional TODO: flash error in session
            return $response
                ->withHeader('Location', $landing_page . '/dashboard')
                ->withStatus(302);
        }

        // Back link to dashboard
        $landing_page = rtrim($settings['landing_page'], '/');
        $backLink = $landing_page . '/dashboard';

        // Render Twig user info page
        return $userInfoView->showUserInfo(
            $view,
            $response,
            $settings,
            $user,
            $backLink
        );
    }

}


?>