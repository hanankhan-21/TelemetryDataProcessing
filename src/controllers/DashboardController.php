<?php 

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DashboardController
{

    
    public function __construct() {}
    public function __destruct() {}

    
    public function outputDashboardPage($container, Request $request, Response $response): Response
    {

        
        $view          = $container->get('view');
        $settings      = $container->get('settings');
        $dashboardView = $container->get('dashboardView');
        $db            = $container->get('database');

        // ----------------------------------------------
        // 1. Ensure session is running
        // ----------------------------------------------
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
      $userName = $_SESSION['user_name'] ?? 'User';


        


        // ----------------------------------------------
        // 4. Build routes for frontend buttons
        // ----------------------------------------------
        $landing_page = rtrim($settings['landing_page'], '/');

        $routes = [
            'deviceInfoRoute' => $landing_page . '/deviceInfo',
            'userInfoRoute'   => $landing_page . '/userInfo',
            'aboutusRoute'    => $landing_page . '/aboutUs',
            'logsRoute'       => $landing_page . '/logs',
            'logoutRoute'     => $landing_page . '/logout'
        ];

        // ----------------------------------------------
        // 5. Render dashboard with user’s name + routes
        // ----------------------------------------------
        return $dashboardView->createDashboardPage(
            $view,
            $response,
            $settings,
            $userName,
            $routes
        );
    }
}
