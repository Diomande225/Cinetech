<?php
class Database {
    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'cinetech';

    private function __construct() {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
} 