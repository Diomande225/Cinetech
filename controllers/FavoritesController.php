<?php

class FavoritesController {
    private $db;
    private $tmdb;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->tmdb = new TMDBApi();
    }

    // Méthode pour afficher la page des favoris
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /Cinetech/login');
            exit;
        }

        try {
            // Récupérer les favoris de l'utilisateur
            $stmt = $this->db->prepare(
                "SELECT * FROM favorites WHERE user_id = ? ORDER BY created_at DESC"
            );
            $stmt->execute([$_SESSION['user']['id']]);
            $userFavorites = $stmt->fetchAll();

            // Initialiser le tableau des favoris
            $favorites = [];
            
            // Récupérer les détails depuis TMDB seulement s'il y a des favoris
            if (!empty($userFavorites)) {
                foreach ($userFavorites as $favorite) {
                    if ($favorite['media_type'] === 'movie') {
                        $details = $this->tmdb->getMovieDetails($favorite['media_id']);
                    } else {
                        $details = $this->tmdb->getTVShowDetails($favorite['media_id']);
                    }
                    if ($details) {
                        $favorites[] = $details;
                    }
                }
            }

            // Charger la vue des favoris
            require 'views/favorites/index.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            require 'views/404.php';
        }
    }

    // Méthode pour ajouter ou supprimer un favori
    public function toggle() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $mediaId = $input['media_id'] ?? null;
        $mediaType = $input['media_type'] ?? null;

        if (!$mediaId || !$mediaType) {
            http_response_code(400);
            echo json_encode(['error' => 'Paramètres manquants']);
            exit;
        }

        $isFavorite = $this->isFavorite($mediaId, $mediaType, $_SESSION['user']['id']);

        if ($isFavorite) {
            $this->removeFromFavorites($mediaId, $mediaType, $_SESSION['user']['id']);
        } else {
            $this->addToFavorites($mediaId, $mediaType, $_SESSION['user']['id']);
        }

        echo json_encode(['success' => true, 'isFavorite' => !$isFavorite]);
    }

    private function isFavorite($mediaId, $mediaType, $userId) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM favorites WHERE media_id = ? AND media_type = ? AND user_id = ?"
        );
        $stmt->execute([$mediaId, $mediaType, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    private function addToFavorites($mediaId, $mediaType, $userId) {
        $stmt = $this->db->prepare(
            "INSERT INTO favorites (media_id, media_type, user_id, created_at) VALUES (?, ?, ?, NOW())"
        );
        $stmt->execute([$mediaId, $mediaType, $userId]);
    }

    private function removeFromFavorites($mediaId, $mediaType, $userId) {
        $stmt = $this->db->prepare(
            "DELETE FROM favorites WHERE media_id = ? AND media_type = ? AND user_id = ?"
        );
        $stmt->execute([$mediaId, $mediaType, $userId]);
    }
} 