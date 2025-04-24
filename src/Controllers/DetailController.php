<?php
namespace App\Controllers;

use App\Views\View;
use App\Services\TMDBApi;
use App\Models\FavorisModel;
use App\Database\Connection;

class DetailController {
    private $tmdbApi;
    private $favorisModel;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
        $db = Connection::getInstance();
        $this->favorisModel = new FavorisModel($db);
    }

    public function show($mediaType, $id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($mediaType === 'movie') {
            $details = $this->tmdbApi->fetchMovieDetails($id);
        } else if ($mediaType === 'tv') {
            $details = $this->tmdbApi->fetchTvDetails($id);
        } else {
            header('Location: /404');
            exit;
        }

        $isFavori = false;
        if (isset($_SESSION['user_id'])) {
            $isFavori = $this->favorisModel->exists($_SESSION['user_id'], $id, $mediaType);
        }

        $view = new View();
        $view->render('detail', [
            'title' => $details['title'] ?? $details['name'],
            'details' => $details,
            'mediaType' => $mediaType,
            'isFavori' => $isFavori
        ]);
    }

    public function actor($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $actorDetails = $this->tmdbApi->fetchActorDetails($id);
        $actorCredits = $this->tmdbApi->fetchActorCredits($id);
        
        // Récupérer les favoris de l'utilisateur
        $userFavorites = [];
        if (isset($_SESSION['user_id'])) {
            $userFavorites = $this->favorisModel->getUserFavoriteIds($_SESSION['user_id']);
        }

        $view = new View();
        $view->render('actor', [
            'title' => $actorDetails['name'],
            'actor' => $actorDetails,
            'credits' => $actorCredits,
            'id' => $id,
            'userFavorites' => $userFavorites
        ]);
    }
}