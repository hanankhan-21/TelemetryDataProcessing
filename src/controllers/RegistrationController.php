<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RegistrationController
{
    public function outputRegisterPage($container, Response $response)
    {
        $view     = $container->get('view');
        $settings = $container->get('settings');
        $regView  = $container->get('registerationView');

        return $regView->createRegisterationPage(
            $view,
            $response,
            $settings,
            false,
            ''
        );
    }

    public function registerUser($container, Request $request, Response $response)
    {
        $view     = $container->get('view');
        $settings = $container->get('settings');
        $regView  = $container->get('registerationView');
        $model    = $container->get('registerationModel');
        $db       = $container->get('database');

        $data = (array)$request->getParsedBody();

        $fullname    = trim($data['full_name'] ?? '');
        $email       = trim($data['email'] ?? '');
        $phone       = trim($data['phone_number'] ?? '');
        $password    = trim($data['password'] ?? '');
        $confirmPass = trim($data['confirm_password'] ?? '');

        // ===== VALIDATIONS =====
        if ($fullname === '' || $email === '' || $phone === '' || $password === '' || $confirmPass === '') {
            return $regView->createRegisterationPage($view, $response, $settings, true, "Please fill all fields.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $regView->createRegisterationPage($view, $response, $settings, true, "Invalid email format.");
        }

        if ($password !== $confirmPass) {
            return $regView->createRegisterationPage($view, $response, $settings, true, "Passwords do not match.");
        }

        // Check if email already exists (via Database helper)
        if ($db->userExists($email)) {
            return $regView->createRegisterationPage($view, $response, $settings, true, "Email already registered.");
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use your model to register the user
      // Use your model to register the user
$result = $model->registerUser(
    $db,
    $fullname,
    $email,
    $phone,
    $hashedPassword
);

if (!$result) {
    // Get DB error if any (for debugging)
    $error = $db->getLastError();
    $message = "Failed to create user.";
    if (!empty($error)) {
        // TEMP: show actual SQL error to you
        $message .= " DB Error: " . $error;
    }

    return $regView->createRegisterationPage($view, $response, $settings, true, $message);
}


        // OPTIONAL: Store user in session (auto login)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $result;

        $landing_page = rtrim($settings['landing_page'], '/');
        return $response
            ->withHeader('Location', $landing_page . '/registerSuccess')
            ->withStatus(302);
    }
}
