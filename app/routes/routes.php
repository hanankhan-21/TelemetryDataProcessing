<?php



use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;

// --- AUTH MIDDLEWARE ---
$authMiddleware = function (Request $request, RequestHandlerInterface $handler) use ($app) {
    $container    = $app->getContainer();
    $settings     = $container->get('settings');
    $landing_page = rtrim($settings['landing_page'], '/');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Session timeout in seconds (e.g. 30 minutes)
    $timeout = 30 * 60;

    // Check if user is logged in
    $loggedIn   = $_SESSION['logged_in'] ?? false;
    $lastActive = $_SESSION['last_active'] ?? 0;
    $now        = time();

    // If not logged in → redirect to login
    if (!$loggedIn) {
        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Location', $landing_page . '/')
            ->withStatus(302);
    }

    // If timeout exceeded → destroy session and redirect to login
    if ($lastActive > 0 && ($now - $lastActive) > $timeout) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Location', $landing_page . '/?session=expired')
            ->withStatus(302);
    }

    // Refresh last active timestamp
    $_SESSION['last_active'] = $now;

    // All good, continue to actual route handler
    return $handler->handle($request);
};


require "login.php";
require "registeration.php";
require "api.php";
require "dashboard.php";
require "logout.php";
require "userInformation.php";
require "deviceInfo.php";
