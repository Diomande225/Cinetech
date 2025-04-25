<?php
/**
 * Script pour créer un administrateur
 * 
 * Utilisation:
 * php scripts/create_admin.php <user_id>
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../vendor/autoload.php';

// Fonction pour créer un administrateur
function createAdmin($userId) {
    $db = \App\Database\Connection::getInstance();
    
    // Vérifier si l'utilisateur existe
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "Erreur : L'utilisateur avec l'ID $userId n'existe pas.\n";
        return false;
    }
    
    // Vérifier si l'utilisateur est déjà admin
    $stmt = $db->prepare("SELECT * FROM admin WHERE user_id = :userId");
    $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "L'utilisateur {$user['username']} est déjà administrateur.\n";
        return false;
    }
    
    // Ajouter l'utilisateur comme admin
    $stmt = $db->prepare("INSERT INTO admin (user_id) VALUES (:userId)");
    $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "L'utilisateur {$user['username']} est maintenant administrateur.\n";
        return true;
    } else {
        echo "Erreur lors de l'ajout de l'administrateur.\n";
        return false;
    }
}

// Vérifier les arguments
if ($argc < 2) {
    echo "Usage: php scripts/create_admin.php <user_id>\n";
    exit(1);
}

// Récupérer l'ID utilisateur
$userId = intval($argv[1]);

// Créer l'administrateur
createAdmin($userId); 