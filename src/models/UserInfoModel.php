<?php


namespace Models;

class UserInfoModel{

 
    public function getUserInfo($email, $db){

      $user = $db->retrieveUser($email);

      return $user;

    }




}




?>