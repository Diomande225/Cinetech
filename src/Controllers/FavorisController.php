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
        error_log("Début de getFavorisWithDetails pour l'utilisateur: " . $userId);
        $favoris = $this->favorisModel->getFavoris($userId);
        error_log("Favoris bruts récupérés: " . print_r($favoris, true));
        $detailedFavoris = [];

        foreach ($favoris as $favori) {
            error_log("Traitement du favori: " . print_r($favori, true));
            $details = null;
            if ($favori['media_type'] === 'movie') {
                error_log("Récupération des détails du film: " . $favori['item_id']);
                $details = $this->tmdbApi->fetchMovieDetails($favori['item_id']);
            } else if ($favori['media_type'] === 'tv') {
                error_log("Récupération des détails de la série: " . $favori['item_id']);
                $details = $this->tmdbApi->fetchTvDetails($favori['item_id']);
            }

            if ($details) {
                error_log("Détails récupérés: " . print_r($details, true));
                $details['media_type'] = $favori['media_type'];
                $details['item_id'] = $favori['item_id'];
                $detailedFavoris[] = $details;
            } else {
                error_log("Aucun détail trouvé pour le favori: " . $favori['item_id']);
            }
        }

        error_log("Favoris détaillés finaux: " . print_r($detailedFavoris, true));
        return $detailedFavoris;
    }

    public function addFavori() {
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

            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['status' => 'guest', 'message' => 'Utilisateur non connecté']);
                return;
            }

            $userId = $_SESSION['user_id'];
            
            // Si le favori existe déjà, on considère que c'est un succès
            if ($this->favorisModel->exists($userId, $itemId, $mediaType)) {
                echo json_encode(['status' => 'success', 'message' => 'Favori déjà présent']);
                return;
            }

            $this->favorisModel->addFavori($userId, $itemId, $mediaType);
            echo json_encode(['status' => 'success', 'message' => 'Favori ajouté avec succès']);

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

            error_log("Tentative de suppression de favori - ItemID: $itemId, MediaType: $mediaType");
            error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'non défini'));
            error_log("Request URI: " . $_SERVER['REQUEST_URI']);
            error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);

            if (!$itemId || !$mediaType) {
                throw new \Exception('ID ou type de média manquant');
            }

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                // On essaie de supprimer le favori même s'il n'existe pas
                $this->favorisModel->removeFavori($userId, $itemId, $mediaType);
                error_log("Favori supprimé avec succès");
                echo json_encode(['status' => 'success', 'message' => 'Favori supprimé avec succès']);
            } else {
                error_log("Utilisateur non connecté");
                echo json_encode(['status' => 'guest', 'message' => 'Utilisateur non connecté']);
            }
        } catch (\Exception $e) {
            error_log('Erreur dans removeFavori: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}