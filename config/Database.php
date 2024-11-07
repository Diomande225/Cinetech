<?php
try {
    $db = new PDO(
        'mysql:host=localhost;dbname=cinetech;charset=utf8',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
} 