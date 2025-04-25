<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class CommentModel {
    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function addComment($userId, $itemId, $itemType, $content) {
        try {
            $query = "INSERT INTO comments (user_id, item_id, item_type, content, created_at) 
                      VALUES (:userId, :itemId, :itemType, :content, NOW())";
            
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([
                ':userId' => $userId,
                ':itemId' => $itemId,
                ':itemType' => $itemType,
                ':content' => $content
            ]);

            if (!$success) {
                error_log("Erreur SQL: " . print_r($stmt->errorInfo(), true));
                return false;
            }

            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getComments($itemId, $itemType) {
        error_log("=== getComments appelé ===");
        error_log("itemId: $itemId");
        error_log("itemType: $itemType");

        try {
            $query = "SELECT c.*, u.username 
                      FROM comments c 
                      JOIN users u ON c.user_id = u.id 
                      WHERE c.item_id = :itemId 
                      AND c.item_type = :itemType 
                      ORDER BY c.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':itemId' => $itemId,
                ':itemType' => $itemType
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Résultats trouvés: " . count($results));
            return $results;

        } catch (\PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            return [];
        }
    }

    public function getCommentById($commentId) {
        try {
            $query = "SELECT c.*, u.username 
                      FROM comments c 
                      JOIN users u ON c.user_id = u.id 
                      WHERE c.id = :commentId";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([':commentId' => (int)$commentId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du commentaire: " . $e->getMessage());
            return null;
        }
    }

    public function getReplies($commentId) {
        $sql = "SELECT c.*, u.username 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.parent_id = ? 
                ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$commentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteComment($commentId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
            return $stmt->execute([$commentId]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du commentaire: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les commentaires pour l'administration
     * 
     * @return array Liste de tous les commentaires avec leurs détails
     */
    public function getAllCommentsWithDetails() {
        try {
            $query = "SELECT c.*, u.username,
                      CASE 
                        WHEN c.item_type = 'movie' THEN 'Film'
                        ELSE 'Série' 
                      END as content_type
                      FROM comments c
                      LEFT JOIN users u ON c.user_id = u.id
                      ORDER BY c.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des commentaires (admin): " . $e->getMessage());
            return [];
        }
    }

    public function getDb() {
        return $this->db;
    }
}