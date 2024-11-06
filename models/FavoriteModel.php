<?php
class FavoriteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getUserFavorites($userId) {
        $query = "SELECT * FROM favorites WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleFavorite($userId, $contentId, $contentType) {
        if ($this->isFavorite($userId, $contentId, $contentType)) {
            return $this->removeFavorite($userId, $contentId, $contentType);
        }
        return $this->addFavorite($userId, $contentId, $contentType);
    }

    private function isFavorite($userId, $contentId, $contentType) {
        $query = "SELECT COUNT(*) FROM favorites 
                 WHERE user_id = :user_id 
                 AND content_id = :content_id 
                 AND content_type = :content_type";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId,
            'content_id' => $contentId,
            'content_type' => $contentType
        ]);
        return $stmt->fetchColumn() > 0;
    }

    private function addFavorite($userId, $contentId, $contentType) {
        $query = "INSERT INTO favorites (user_id, content_id, content_type) 
                 VALUES (:user_id, :content_id, :content_type)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'content_id' => $contentId,
            'content_type' => $contentType
        ]);
    }

    private function removeFavorite($userId, $contentId, $contentType) {
        $query = "DELETE FROM favorites 
                 WHERE user_id = :user_id 
                 AND content_id = :content_id 
                 AND content_type = :content_type";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'content_id' => $contentId,
            'content_type' => $contentType
        ]);
    }
} 