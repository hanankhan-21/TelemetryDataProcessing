<?php

namespace Models;


class LoginModel{



public function authenticateUser($db, $validatedEmail, $validatedPassword)
{
    $user = $db->retrieveUser($validatedEmail);

    if ($user === false) {
        // No such user found
        return ['success' => false, 'message' => '❌ Invalid email or password.'];
    }

    $password = $user['password']; // access password column

    if (!password_verify($validatedPassword, $hashedPassword)) {
        // Password does not match
        return ['success' => false, 'message' => '❌ Invalid email or password.'];
    }

    // ✅ Successful login
    return [
        'success' => true,
        'message' => '✅ Login successful!',
        'user'    => $user, // optional: return user data if needed
    ];
}
  





}





?>