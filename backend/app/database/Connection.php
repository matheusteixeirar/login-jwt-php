<?php 

namespace app\database;

use PDO;
use PDOException;

class Connection {

    public static function connect() {

        $host = $_ENV['HOST'];  
        $dbname = $_ENV['DBNAME'];
        $username = $_ENV['USERNAME'];
        $password = $_ENV['PASSWORD'];

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname",
                $username,
                $password
            );
            return $pdo;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }  

    }

}