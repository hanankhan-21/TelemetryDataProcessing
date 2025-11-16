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


 public static function storeMessages()
{
    $query_string  = "INSERT INTO messages SET ";
    $query_string .= "device_id = :device_id, ";
    $query_string .= "switch1 = :switch1, ";
    $query_string .= "switch2 = :switch2, ";
    $query_string .= "switch3 = :switch3, ";
    $query_string .= "switch4 = :switch4, ";
    $query_string .= "fan = :fan, ";
    $query_string .= "device_temperature = :device_temperature, ";
    $query_string .= "last_key_entered = :last_key_entered, ";
    $query_string .= "received_date = :received_date ";

    return $query_string;
}


}
?>
