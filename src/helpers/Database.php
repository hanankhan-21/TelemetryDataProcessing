<?php

namespace Helpers;


use DI\Container;
use Psr\Container\ContainerInterface;
use PDO;
use PDOException;
use Helpers\SQLQueries;
use Exception;
class Database{
  
     private $host;
     private $pass;
     private $dbName;
     private $user;
     private $port; 
     private $charset;
     private $db;

     private $errors;

     private $prepared_statement;


     public function __construct(ContainerInterface $container){
 
      $dbSettings = $container->get('settings')['db'];
        $this->host    = $dbSettings['host'];
        $this->dbName  = $dbSettings['dbname'];
        $this->user    = $dbSettings['user'];
        $this->pass    = $dbSettings['pass'];
        $this->charset = $dbSettings['charset'];
        $this->port    = $dbSettings['port'];


     }


      public function __destruct()
    {
        // Optional: close DB connection
        $this->db = null;
    }

    public function connectToDatabase(){
       if($this->db instanceof PDO){
        return $this->db;
       }
    
    $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset={$this->charset}";
    try {
            $this->db = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // For now, print and exit (in production: log instead)
            die('Database connection failed: ' . $e->getMessage());
        }

       
        return $this->db;

    }



    public function testConnection()
{
    try {
        $pdo = $this->connectToDatabase();
        echo "✅ Database connection successful!";
    } catch (PDOException $e) {
        echo "❌ Connection failed: " . $e->getMessage();
    }
}

  public function safeQuery($query, array $params = null ){
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try {
            $this->prepared_statement = $this->db->prepare($query);
            $execute_result = $this->prepared_statement->execute($query_parameters);
            $this->errors['execute-OK'] = $execute_result;
        } catch (PDOException $exception_object) {
            $error_message = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
        return $this->errors['db_error'];


    }


  public function addUser($validated_fullName, $validated_email, $validated_phoneNumber, $hashed_password){

        try{
            $sql_query = SQLQueries::addNewUser();
            $queryParameters = [
                ':fullName' => $validated_fullName,
                ':email' => $validated_email,
                ':phoneNumber' => $validated_phoneNumber,
                ':password' => $hashed_password,
            ];

            $Result = $this->safeQuery($sql_query, $queryParameters);


        }
        catch(Exception $e){
           $this->errors['db_error']  = true;
            $this->errors['sql_error'] = $e->getMessage();
            return false;
        }

        if ($Result === false) {
            return true;
        } else {
            return "Database error";
        }
    }

    public function userExists($validatedEmail)
    {
        try {
            $pdo       = $this->connectToDatabase();
            $sqlQuery  = SQLQueries::returnUserDetails();
            $stmt      = $pdo->prepare($sqlQuery);
            $stmt->execute([
                ':email' => $validatedEmail,
            ]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // If a row is returned, user exists
            if ($row !== false) {
                // You can either return true or return $row depending on what you need
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // optional: store error
            $this->errors['db_error']  = true;
            $this->errors['sql_error'] = $e->getMessage();
            return false;
        }
    }



}



?>