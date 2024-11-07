<?php

class ApiController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function toggleFavorite() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user']['id'];
        $mediaId = $data['media_id'] ?? null;
        $mediaType = $data['media_type'] ?? null;

        if (!$mediaId || !$mediaType) {
            http_response_code(400);
            echo json_encode(['error' => 'Données invalides']);
            return;
        }

        try {
            // Vérifier si déjà en favoris
            $stmt = $this->db->prepare(
                "SELECT id FROM favorites 
                WHERE user_id = ? AND media_id = ? AND media_type = ?"
            );
            $stmt->execute([$userId, $mediaId, $mediaType]);
            $favorite = $stmt->fetch();

            if ($favorite) {
                // Supprimer des favoris
                $stmt = $this->db->prepare(
                    "DELETE FROM favorites 
                    WHERE user_id = ? AND media_id = ? AND media_type = ?"
                );
                $stmt->execute([$userId, $mediaId, $mediaType]);
                $isFavorite = false;
            } else {
                // Ajouter aux favoris
                $stmt = $this->db->prepare(
                    "INSERT INTO favorites (user_id, media_id, media_type) 
                    VALUES (?, ?, ?)"
                );
                $stmt->execute([$userId, $mediaId, $mediaType]);
                $isFavorite = true;
            }

            echo json_encode([
                'success' => true,
                'isFavorite' => $isFavorite
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }
} 