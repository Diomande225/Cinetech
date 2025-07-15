<?php
namespace App\Models;

use App\Database\Connection;

class User {
    public static function create($username, $email, $password) {
        $db = Connection::getInstance();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, is_active) VALUES (:username, :email, :password, 1)");
        try {
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_BCRYPT),
            ]);
            return ['success' => true, 'user_id' => $db->lastInsertId()];
        } catch (\PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
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

    /**
     * Met à jour les informations d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @param array $data Données à mettre à jour (username, email)
     * @return bool Succès ou échec
     */
    public static function updateUser($userId, $data) {
        $db = Connection::getInstance();
        
        $setFields = [];
        $params = [':userId' => $userId];
        
        // Construire la requête dynamiquement selon les champs fournis
        if (isset($data['username'])) {
            $setFields[] = "username = :username";
            $params[':username'] = $data['username'];
        }
        
        if (isset($data['email'])) {
            $setFields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        
        // Si aucun champ à mettre à jour
        if (empty($setFields)) {
            return false;
        }
        
        $query = "UPDATE users SET " . implode(', ', $setFields) . " WHERE id = :userId";
        $stmt = $db->prepare($query);
        
        return $stmt->execute($params);
    }
    
    /**
     * Vérifie si un nom d'utilisateur existe déjà (sauf pour l'utilisateur spécifié)
     * 
     * @param string $username Nom d'utilisateur à vérifier
     * @param int $excludeUserId ID de l'utilisateur à exclure de la vérification
     * @return bool True si le nom d'utilisateur existe déjà
     */
    public static function usernameExists($username, $excludeUserId = null) {
        $db = Connection::getInstance();
        $query = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = [':username' => $username];
        
        if ($excludeUserId !== null) {
            $query .= " AND id != :excludeUserId";
            $params[':excludeUserId'] = $excludeUserId;
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Vérifie si un email existe déjà (sauf pour l'utilisateur spécifié)
     * 
     * @param string $email Email à vérifier
     * @param int $excludeUserId ID de l'utilisateur à exclure de la vérification
     * @return bool True si l'email existe déjà
     */
    public static function emailExists($email, $excludeUserId = null) {
        $db = Connection::getInstance();
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $params = [':email' => $email];
        
        if ($excludeUserId !== null) {
            $query .= " AND id != :excludeUserId";
            $params[':excludeUserId'] = $excludeUserId;
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }
}