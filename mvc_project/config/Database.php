<?php
namespace App\Config;

class Database {
    // Instance unique de la classe (Pattern Singleton)
    private static $instance = null;
    private $connection;

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct() {
        // Charge la configuration depuis config.php
        $config = require 'config.php';
        
        try {
            // Crée une nouvelle connexion PDO
            $this->connection = new \PDO(
                "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset=utf8mb4",
                $config['db']['user'],
                $config['db']['password']
            );
            // Configure PDO pour lancer des exceptions en cas d'erreur
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Méthode pour obtenir l'instance unique
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Retourne la connexion PDO
    public function getConnection() {
        return $this->connection;
    }
}