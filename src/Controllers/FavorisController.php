<?php
namespace App\Controllers;

use App\Views\View;
use App\Models\FavorisModel;
use App\Database\Connection;
use App\Services\TMDBApi;

class FavorisController {
    private $favorisModel;
    private $tmdbApi;

    public function __construct() {
        $db = Connection::getInstance();
        $this->favorisModel = new FavorisModel($db);
        $this->tmdbApi = new TMDBApi();
    }

    public function favoris() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Debug
        error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'non défini'));
        error_log("Session username: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'non défini'));
        
        if (!isset($_SESSION['user_id'])) {
            error_log("Redirection vers login car user_id non défini");
            header('Location: /Cinetech/login');
            exit;
        }

        $favoris = $this->getFavorisWithDetails($_SESSION['user_id']);
        
        $view = new View();
        $view->render('favoris', [
            'title' => 'Mes Favoris',
            'favoris' => $favoris
        ]);
    }

    private function getFavorisWithDetails($userId) {
        $favoris = $this->favorisModel->getFavoris($userId);
        $detailedFavoris = [];

        foreach ($favoris as $favori) {
            $details = null;
            if ($favori['media_type'] === 'movie') {
                $details = $this->tmdbApi->fetchMovieDetails($favori['item_id']);
            } else if ($favori['media_type'] === 'tv') {
                $details = $this->tmdbApi->fetchTvDetails($favori['item_id']);
            }

            if ($details) {
                $details['media_type'] = $favori['media_type'];
                $details['item_id'] = $favori['item_id'];
                $detailedFavoris[] = $details;
            }
        }

        return $detailedFavoris;
    }

    public function addFavori() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            error_log('Received data: ' . print_r($data, true));
            
            if (!$data) {
                throw new \Exception('Données invalides');
            }

            $itemId = $data['item_id'] ?? null;
            $mediaType = $data['media_type'] ?? null;

            error_log("ItemID: $itemId, MediaType: $mediaType");

            if (!$itemId || !$mediaType) {
                throw new \Exception('ID ou type de média manquant');
            }

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                error_log("UserID: $userId");
                
                if (!$this->favorisModel->exists($userId, $itemId, $mediaType)) {
                    $this->favorisModel->addFavori($userId, $itemId, $mediaType);
                    echo json_encode(['status' => 'success', 'message' => 'Favori ajouté avec succès']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Ce favori existe déjà']);
                }
            } else {
                echo json_encode(['status' => 'guest', 'message' => 'Utilisateur non connecté']);
            }
        } catch (\Exception $e) {
            error_log('Error in addFavori: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function removeFavori() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new \Exception('Données invalides');
            }

            $itemId = $data['item_id'] ?? null;
            $mediaType = $data['media_type'] ?? null;

            if (!$itemId || !$mediaType) {
                throw new \Exception('ID ou type de média manquant');
            }

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                if ($this->favorisModel->exists($userId, $itemId, $mediaType)) {
                    $this->favorisModel->removeFavori($userId, $itemId, $mediaType);
                    echo json_encode(['status' => 'success', 'message' => 'Favori supprimé avec succès']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => "Le favori n'existe pas"]);
                }
            } else {
                echo json_encode(['status' => 'guest', 'message' => 'Utilisateur non connecté']);
            }
        } catch (\Exception $e) {
            error_log('Erreur dans removeFavori: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}