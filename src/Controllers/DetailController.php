<?php
namespace App\Controllers;

use App\Views\View;
use App\Services\TMDBApi;
use App\Models\FavorisModel;
use App\Database\Connection;
use App\Lang\Language;

class DetailController extends BaseController {
    private $tmdbApi;
    private $favorisModel;

    public function __construct() {
        parent::__construct(); // Appel au constructeur parent qui initialise $this->view et $this->language
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

        // Ajouter des clés manquantes pour les traductions
        $this->view->render('detail', [
            'title' => $details['title'] ?? $details['name'],
            'details' => $details,
            'mediaType' => $mediaType,
            'isFavori' => $isFavori,
            'basePath' => '/Cinetech' // Ajouter le chemin de base
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

        // Traduire la biographie si nécessaire
        if (isset($actorDetails['biography'])) {
            $actorDetails['biography_translated'] = translateExternal($actorDetails['biography']);
        }

        $this->view->render('actor', [
            'title' => $actorDetails['name'],
            'actor' => $actorDetails,
            'credits' => $actorCredits,
            'id' => $id,
            'userFavorites' => $userFavorites,
            'basePath' => '/Cinetech' // Ajouter le chemin de base
        ]);
    }
}