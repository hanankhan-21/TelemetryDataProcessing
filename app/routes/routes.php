<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Helpers\Database;

/**
 * Homepage: simple registration form
 */
$app->get('/', function (Request $request, Response $response) {
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>User Registration Test</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f6fa;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            form {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                width: 350px;
            }
            h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            label {
                display: block;
                margin-top: 10px;
                font-weight: bold;
            }
            input {
                width: 100%;
                padding: 8px;
                margin-top: 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                width: 100%;
                margin-top: 15px;
                padding: 10px;
                border: none;
                border-radius: 4px;
                background-color: #0984e3;
                color: #fff;
                font-size: 16px;
                cursor: pointer;
            }
            button:hover {
                background-color: #74b9ff;
            }
            .msg {
                text-align: center;
                margin-top: 10px;
                font-size: 15px;
            }
        </style>
    </head>
    <body>
        <form action="register" method="POST">
            <h2>Register User</h2>

            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Submit</button>
        </form>
    </body>
    </html>
    ';

    $response->getBody()->write($html);
    return $response;
});

/**
 * Register route (for now placed below homepage)
 */
$app->post('/register', function (Request $request, Response $response) use ($container) {
    /** @var \Helpers\Database $db */
    $db = $container->get(Database::class);

    $data = $request->getParsedBody();

    $fullName    = $data['full_name']    ?? null;
    $email       = $data['email']        ?? null;
    $phoneNumber = $data['phone_number'] ?? null;
    $password    = $data['password']     ?? null;

    if (!$fullName || !$email || !$phoneNumber || !$password) {
        $response->getBody()->write("<p class='msg' style='color:red;'>❌ Missing required fields.</p>");
        return $response;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($db->userExists($email)) {
        $response->getBody()->write("<p class='msg' style='color:red;'>❌ User already exists.</p>");
        return $response;
    }

    $success = $db->addUser($fullName, $email, $phoneNumber, $hashedPassword);

    if ($success) {
        $response->getBody()->write("<p class='msg' style='color:green;'>✅ User registered successfully!</p>");
    } else {
        $error = method_exists($db, 'getLastError') ? ($db->getLastError() ?? 'Unknown DB error') : 'Unknown DB error';
        $response->getBody()->write("<p class='msg' style='color:red;'>❌ Failed to register user.<br>" . nl2br($error) . "</p>");
    }

    return $response;
});
