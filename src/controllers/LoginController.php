<?php 

namespace Controllers;

use Views\LoginView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class LoginController
{
    public function __construct() {}
    public function __destruct() {}

    public function outputHtmlPage($container, Request $request, Response $response): Response
    {
        $loginView = $container->get('loginView');
        $view      = $container->get('view');
        $settings  = $container->get('settings');
        // Extract user input

        $loginFailureReason = null;
        return $loginView->createHomePage($view, $settings, $response, $loginFailureReason);
    }

    public function loginUser($container, Request $request, Response $response): Response
    {
        $view       = $container->get('view');
        $settings   = $container->get('settings');
        $db         = $container->get('database');
        $loginModel = $container->get('loginModel');
        $loginView  = $container->get('loginView');

        $data          = (array) $request->getParsedBody();
        $user_email    = trim($data['email'] ?? '');
        $user_password = trim($data['password'] ?? '');

        $validated_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);

        if (!$validated_email || $user_password === '') {
            $login_failure_reason = 'Please enter a valid email and password.';
            return $loginView->createHomePage($view, $settings, $response, $login_failure_reason);
        }

        try {
            // Authenticate user via model (returns bool)
            $isAuthenticated = $loginModel->authenticateUser($db, $validated_email, $user_password);

            if ($isAuthenticated === false) {
                $login_failure_reason = 'Invalid email or password';
                return $loginView->createHomePage($view, $settings, $response, $login_failure_reason);
            }

            // Fetch user row to store extra info in session
            $userRow = $db->retrieveUser($validated_email);

            // --- SESSION HANDLING ---
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Prevent session fixation
            session_regenerate_id(true);

            $_SESSION['email']       = $validated_email;
            $_SESSION['user_name']   = $userRow['full_name'] ?? 'User';
            $_SESSION['logged_in']   = true;
            $_SESSION['last_active'] = time(); // for timeout

            // Login successful — redirect to dashboard
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
