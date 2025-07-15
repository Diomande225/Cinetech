<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Vérifie si un utilisateur est administrateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool True si l'utilisateur est admin, false sinon
     */
    public function isUserAdmin($userId) {
        try {
            $query = "SELECT * FROM admin WHERE user_id = :userId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return ($stmt->rowCount() > 0);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la vérification admin: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ajoute un nouvel administrateur
     * 
     * @param int $userId ID de l'utilisateur à promouvoir
     * @return bool Succès ou échec
     */
    public function addAdmin($userId) {
        try {
            // Vérifier que l'utilisateur n'est pas déjà admin
            if ($this->isUserAdmin($userId)) {
                return true; // Déjà admin, retourne succès
            }
            
            $query = "INSERT INTO admin (user_id) VALUES (:userId)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'ajout d'un admin: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprime un administrateur
     * 
     * @param int $userId ID de l'utilisateur à dégrader
     * @return bool Succès ou échec
     */
    public function removeAdmin($userId) {
        try {
            $query = "DELETE FROM admin WHERE user_id = :userId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression d'un admin: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère tous les administrateurs avec leurs détails
     * 
     * @return array Liste des administrateurs
     */
    public function getAllAdmins() {
        try {
            $query = "SELECT a.*, u.username, u.email 
                      FROM admin a
                      JOIN users u ON a.user_id = u.id
                      ORDER BY a.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des admins: " . $e->getMessage());
            return [];
        }
    }
} 