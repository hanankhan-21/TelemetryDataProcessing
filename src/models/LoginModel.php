<?php

namespace Models;


class LoginModel{



public function authenticateUser($db, $validatedEmail, $validatedPassword): bool
{
    $user = $db->retrieveUser($validatedEmail);

    if ($user === false) {
        return false;
    }

    $hashedPassword = $user['password'];

    return password_verify($validatedPassword, $hashedPassword);
}





}





?>