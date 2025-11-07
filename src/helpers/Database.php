<?php

namespace Helpers;


use DI\Container;
use Psr\Container\ContainerInterface;
use PDO;
use PDOException;
class Database{
  
     private $host;
     private $pass;
     private $dbName;
     private $user;
     private $port; 
     private $charset;
     private $db;


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



}



?>