<?php 

namespace Helpers;

class SQLQueries
{
    public function __construct() {}
    public function __destruct() {}

    public static function addNewUser()
    {
        $query_string  = "INSERT INTO users ";
        $query_string .= "SET full_name = :fullName, ";
        $query_string .= "email = :email, ";
        $query_string .= "phone_number = :phoneNumber, ";
        $query_string .= "password = :password ";
        return $query_string;
    }

    public static function returnUserDetails()
    {
        $query_string  = "SELECT * FROM users ";
        $query_string .= "WHERE email = :email";
        return $query_string;
    }
}
?>
