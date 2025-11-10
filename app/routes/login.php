<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;
use Helpers\Database;
use Models\LoginModel;

/**
 * --------------------------------------------------------------------------
 * GET /
 * Display the login page
 * --------------------------------------------------------------------------
 */
$app->get('/', function (Request $request, Response $response) use ($app, $container) {
    /** @var Twig $twig */
    $twig = $container->get('view');

    // ✅ Get base path from Slim (set in bootstrap.php)
    $basePath = rtrim($app->getBasePath(), '/');

    return $twig->render($response, 'home.html.twig', [
        'login_failure_reason' => null,
        'css_path'  => $basePath . '/assets/css/style.css',
        'register'  => $basePath . '/register',
        'base_path' => $basePath,
    ]);
});

/**
 * --------------------------------------------------------------------------
 * POST /loginUser
 * Handle login submission
 * --------------------------------------------------------------------------
 */
$app->post('/loginUser', function (Request $request, Response $response) use ($app, $container) {
    /** @var Twig $twig */
    $twig = $container->get('view');
    /** @var Database $db */
    $db = $container->get(Database::class);
    /** @var LoginModel $loginModel */
    $loginModel = $container->get(LoginModel::class);

    $basePath = rtrim($app->getBasePath(), '/');

    $data = (array) $request->getParsedBody();
    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');

    // ---- Validation ----
    if (empty($email) || empty($password)) {
        return $twig->render($response, 'home.html.twig', [
            'login_failure_reason' => 'Please enter both email and password.',
            'css_path'  => $basePath . '/assets/css/style.css',
            'register'  => $basePath . '/register',
            'base_path' => $basePath,
        ]);
    }

    // ---- Authenticate ----
    $result = $loginModel->authenticateUser($db, $email, $password);

    if (empty($result['success'])) {
        return $twig->render($response, 'home.html.twig', [
            'login_failure_reason' => $result['message'] ?? 'Invalid credentials.',
            'css_path'  => $basePath . '/assets/css/style.css',
            'register'  => $basePath . '/register',
            'base_path' => $basePath,
        ]);
    }

    // ---- Redirect on success ----
    $responseFactory = new ResponseFactory();
    return $responseFactory->createResponse(302)
        ->withHeader('Location', $basePath . '/dashboard');
});
