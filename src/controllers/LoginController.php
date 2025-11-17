<?php 

namespace Controllers;
use Views\LoginView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;


class LoginController{


    public function __construct() {}
    public function __destruct() {}


     public function outputHtmlPage($container, Request $request, Response $response): Response
    {
        $loginView = $container->get('loginView');
        $view      = $container->get('view');
        $settings  = $container->get('settings');

        $loginFailureReason = null;
        return $loginView->createHomePage($view, $settings, $response, $loginFailureReason);
    }

  public function loginUser($container, Request $request, Response $response): Response
{
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $view       = $container->get('view');
    $settings   = $container->get('settings');
    $db         = $container->get('database');
    $loginModel = $container->get('loginModel');
    $loginView  = $container->get('loginView');

    $data           = (array) $request->getParsedBody();
    $user_email     = $data['email'] ?? '';
    $user_password  = $data['password'] ?? '';
    $validated_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);

    // Store in session
    $_SESSION['email'] = $validated_email;

    try {
        $isAuthenticated = $loginModel->authenticateUser($db, $user_email, $user_password);

        if ($isAuthenticated === false) {
            $login_failure_reason = 'Invalid email or password';
            return $loginView->createHomePage($view, $settings, $response, $login_failure_reason);
        }

        // Redirect to dashboard
        $landing_page = rtrim($settings['landing_page'], '/');
        return $response
            ->withHeader('Location', $landing_page . '/dashboard')
            ->withStatus(302);

    } catch (Exception $e) {
        $login_failure_reason = 'An unexpected error occurred. Please try again later.';
        return $loginView->createHomePage($view, $settings, $response, $login_failure_reason);
    }
}


}


?>