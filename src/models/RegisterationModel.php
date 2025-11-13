<?php

namespace Models;

use Helpers\Database;
class RegisterationModel{



private function registerUser($db, $validatedFullname, $validatedEmail, $validatedPhoneNumber, $hashedPassword)
{
    // 1) Check DB connection (optional because connectToDatabase() already dies on failure)
    $connection = $db->connectToDatabase();

    if (!($connection instanceof \PDO)) {
        echo "Could not connect to database.";
        return;
    }

    // 2) Check if user already exists
    if ($db->userExists($validatedEmail)) {
        echo "A user with this email already exists.";
        return;
    }

    // 3) Insert user
    $result = $db->addUser($validatedFullname, $validatedEmail, $validatedPhoneNumber, $hashedPassword);

    if ($result === true) {
        echo "User registered successfully!";
    } else {
        echo "Failed to register user.<br>";
        echo nl2br($db->getLastError() ?? 'Unknown database error');
    }
}







}



?>