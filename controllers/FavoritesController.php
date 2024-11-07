<?php
class FavoritesController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function toggle() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $type = $data['type'];
        $userId = $_SESSION['user']['id'];

        try {
            // Vérifier si déjà en favoris
            $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND content_id = ? AND content_type = ?");
            $stmt->execute([$userId, $id, $type]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Supprimer des favoris
                $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND content_id = ? AND content_type = ?");
                $stmt->execute([$userId, $id, $type]);
                $action = 'removed';
            } else {
                // Ajouter aux favoris
                $stmt = $this->db->prepare("INSERT INTO favorites (user_id, content_id, content_type) VALUES (?, ?, ?)");
                $stmt->execute([$userId, $id, $type]);
                $action = 'added';
            }

            // Récupérer le nouveau nombre total de favoris
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?");
            $stmt->execute([$userId]);
            $count = $stmt->fetchColumn();

            echo json_encode([
                'success' => true,
                'action' => $action,
                'count' => $count
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function isInFavorites($contentId, $contentType) {
        if (!isset($_SESSION['user'])) return false;
        
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND content_id = ? AND content_type = ?");
        $stmt->execute([$_SESSION['user']['id'], $contentId, $contentType]);
        return $stmt->fetch() !== false;
    }
} 