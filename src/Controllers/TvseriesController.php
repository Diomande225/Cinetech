<?php
namespace App\Controllers;

use App\Views\View;
use App\Services\TMDBApi;
use App\Models\FavorisModel;
use App\Database\Connection;

class TvseriesController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    public function series() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $genre = $_GET['genre'] ?? null;
        $year = $_GET['year'] ?? null;
        $sort = $_GET['sort'] ?? 'popularity.desc';

        $series = $this->tmdbApi->getTvSeries($genre, $year, $sort);
        $genres = $this->tmdbApi->getTvGenres();

        $view = new View();
        $view->render('tvseries', [
            'title' => 'Séries TV',
            'series' => $series['results'],
            'genres' => $genres['genres'],
            'currentGenre' => $genre,
            'currentYear' => $year,
            'currentSort' => $sort
        ]);
    }

    private function getUserFavorites() {
        $userFavorites = [];
        if (isset($_SESSION['user_id'])) {
            $favorisModel = new FavorisModel(Connection::getInstance());
            $userFavorites = $favorisModel->getUserFavoriteIds($_SESSION['user_id']);
        }
        return $userFavorites;
    }
    
    public function index() { // ou films() ou series() selon le contrôleur
        // ... autre code ...
        $userFavorites = $this->getUserFavorites();
        $view->render('home', [ // ou 'movies' ou 'tvseries'
            // ... autres données ...
            'userFavorites' => $userFavorites
        ]);
    }
}