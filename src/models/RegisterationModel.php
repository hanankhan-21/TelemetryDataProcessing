<?php

namespace Models;

use Helpers\Database;

class RegisterationModel
{
    public function registerUser(
        Database $db,
        string $validatedFullname,
        string $validatedEmail,
        string $validatedPhoneNumber,
        string $hashedPassword
    ): bool {
        // Controller already checked userExists, just insert
        return $db->addUser(
            $validatedFullname,
            $validatedEmail,
            $validatedPhoneNumber,
            $hashedPassword
        );
    }
}




?>