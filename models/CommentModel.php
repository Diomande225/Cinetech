<?php
class CommentModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getComments($contentId, $contentType, $limit = 10, $offset = 0) {
        $query = "SELECT c.*, u.username, u.avatar 
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.content_id = :content_id 
                 AND c.content_type = :content_type 
                 AND c.parent_id IS NULL 
                 ORDER BY c.created_at DESC 
                 LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':content_id', $contentId, PDO::PARAM_INT);
        $stmt->bindValue(':content_type', $contentType, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les réponses pour chaque commentaire
        foreach ($comments as &$comment) {
            $comment['replies'] = $this->getReplies($comment['id']);
        }

        return $comments;
    }

    public function getReplies($parentId) {
        $query = "SELECT c.*, u.username, u.avatar 
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.parent_id = :parent_id 
                 ORDER BY c.created_at ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['parent_id' => $parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($userId, $contentId, $contentType, $comment, $parentId = null) {
        $query = "INSERT INTO comments (user_id, content_id, content_type, comment_text, parent_id) 
                 VALUES (:user_id, :content_id, :content_type, :comment_text, :parent_id)";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'content_id' => $contentId,
            'content_type' => $contentType,
            'comment_text' => $comment,
            'parent_id' => $parentId
        ]);
    }

    public function deleteComment($commentId, $userId) {
        $query = "DELETE FROM comments 
                 WHERE id = :comment_id 
                 AND user_id = :user_id";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'comment_id' => $commentId,
            'user_id' => $userId
        ]);
    }

    public function editComment($commentId, $userId, $newText) {
        $query = "UPDATE comments 
                 SET comment_text = :comment_text, 
                     updated_at = CURRENT_TIMESTAMP 
                 WHERE id = :comment_id 
                 AND user_id = :user_id";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'comment_text' => $newText,
            'comment_id' => $commentId,
            'user_id' => $userId
        ]);
    }
} 