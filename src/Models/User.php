<?php
namespace App\Models;

use App\Database\Connection;

class User {
    public static function create($username, $email, $password) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, is_active) VALUES (:username, :email, :password, 1)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
        ]);
    }

    public static function findByEmail($email) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère tous les utilisateurs avec leur statut
     * 
     * @return array Liste de tous les utilisateurs
     */
    public static function getAllUsers() {
        $db = Connection::getInstance();
        $stmt = $db->prepare("
            SELECT u.*, 
                   CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END as is_admin
            FROM users u 
            LEFT JOIN admin a ON u.id = a.user_id
            ORDER BY u.username
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Active un compte utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool Succès ou échec
     */
    public static function activateUser($userId) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE users SET is_active = 1 WHERE id = :userId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Désactive un compte utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool Succès ou échec
     */
    public static function deactivateUser($userId) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE users SET is_active = 0 WHERE id = :userId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Supprime un compte utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool Succès ou échec
     */
    public static function deleteUser($userId) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :userId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}