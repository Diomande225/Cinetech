<?php
class FavoriteController {
    private $favoriteModel;
    private $tmdb;

    public function __construct() {
        $this->favoriteModel = new FavoriteModel();
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        if (!isAuthenticated()) {
            redirect('login');
        }

        $favorites = $this->favoriteModel->getUserFavorites(getCurrentUser()['id']);
        require 'views/favorites/index.php';
    }

    public function toggle() {
        if (!isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        $contentId = $_POST['content_id'];
        $contentType = $_POST['content_type'];
        $userId = getCurrentUser()['id'];

        $result = $this->favoriteModel->toggleFavorite($userId, $contentId, $contentType);
        echo json_encode(['success' => $result]);
    }
} 