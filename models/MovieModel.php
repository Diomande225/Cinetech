<?php
class MovieModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getComments($movieId) {
        $query = "SELECT c.*, u.username 
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.content_id = :movie_id 
                 AND c.content_type = 'movie' 
                 ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['movie_id' => $movieId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($movieId, $userId, $comment, $parentId = null) {
        $query = "INSERT INTO comments (content_id, content_type, user_id, comment_text, parent_id) 
                 VALUES (:content_id, 'movie', :user_id, :comment_text, :parent_id)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'content_id' => $movieId,
            'user_id' => $userId,
            'comment_text' => $comment,
            'parent_id' => $parentId
        ]);
    }

    public function isFavorite($movieId, $userId) {
        $query = "SELECT COUNT(*) FROM favorites 
                 WHERE content_id = :content_id 
                 AND content_type = 'movie' 
                 AND user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'content_id' => $movieId,
            'user_id' => $userId
        ]);
        return $stmt->fetchColumn() > 0;
    }
} 