<?php
class FavoritesController {
    private $db;
    private $tmdb;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /Cinetech/login');
            exit;
        }

        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM favorites WHERE user_id = ? ORDER BY created_at DESC"
            );
            $stmt->execute([$_SESSION['user']['id']]);
            $userFavorites = $stmt->fetchAll();

            $favorites = [];
            foreach ($userFavorites as $favorite) {
                if ($favorite['media_type'] === 'movie') {
                    $details = $this->tmdb->getMovie($favorite['media_id']);
                } else {
                    $details = $this->tmdb->getTVShow($favorite['media_id']);
                }
                
                if ($details) {
                    $favorites[] = array_merge($details, [
                        'media_type' => $favorite['media_type'],
                        'media_id' => $favorite['media_id']
                    ]);
                }
            }

            require 'views/favorites/index.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue";
            header('Location: /Cinetech');
            exit;
        }
    }

    public function toggle() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['media_id']) || !isset($data['media_type'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
            return;
        }

        try {
            $userId = $_SESSION['user']['id'];
            $mediaId = $data['media_id'];
            $mediaType = $data['media_type'];

            $stmt = $this->db->prepare(
                "SELECT id FROM favorites 
                WHERE user_id = ? AND media_id = ? AND media_type = ?"
            );
            $stmt->execute([$userId, $mediaId, $mediaType]);
            $favorite = $stmt->fetch();

            if ($favorite) {
                $stmt = $this->db->prepare(
                    "DELETE FROM favorites 
                    WHERE user_id = ? AND media_id = ? AND media_type = ?"
                );
                $stmt->execute([$userId, $mediaId, $mediaType]);
                $isFavorite = false;
            } else {
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
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function isFavorite($mediaId, $mediaType) {
        if (!isset($_SESSION['user'])) return false;
        
        try {
            $stmt = $this->db->prepare(
                "SELECT 1 FROM favorites 
                WHERE user_id = ? AND media_id = ? AND media_type = ?"
            );
            $stmt->execute([$_SESSION['user']['id'], $mediaId, $mediaType]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
} 