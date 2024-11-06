<?php
class TVShowModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getComments($showId) {
        $query = "SELECT c.*, u.username 
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.content_id = :show_id 
                 AND c.content_type = 'tv' 
                 ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['show_id' => $showId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($showId, $userId, $comment, $parentId = null) {
        $query = "INSERT INTO comments (content_id, content_type, user_id, comment_text, parent_id) 
                 VALUES (:content_id, 'tv', :user_id, :comment_text, :parent_id)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'content_id' => $showId,
            'user_id' => $userId,
            'comment_text' => $comment,
            'parent_id' => $parentId
        ]);
    }

    public function isFavorite($showId, $userId) {
        $query = "SELECT COUNT(*) FROM favorites 
                 WHERE content_id = :content_id 
                 AND content_type = 'tv' 
                 AND user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'content_id' => $showId,
            'user_id' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }
} 