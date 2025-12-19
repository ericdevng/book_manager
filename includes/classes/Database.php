<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        require __DIR__ . '/../../config/db.php';
        $dns = "mysql:host=$servidor;dbname=$base;charset=utf8";

        $this -> pdo = new PDO($dns, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]);
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this -> pdo;
    }
}


?>