<?php
namespace App\Controllers;

use App\Views\View;
use App\Services\TMDBApi;
use App\Models\FavorisModel;
use App\Database\Connection;

class MoviesController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    public function films() {
        error_log("MoviesController::films() called");
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $genre = $_GET['genre'] ?? null;
        $year = $_GET['year'] ?? null;
        $sort = $_GET['sort'] ?? 'popularity.desc';

        error_log("Getting movies with params: genre=$genre, year=$year, sort=$sort");
        $movies = $this->tmdbApi->getMovies($genre, $year, $sort);
        $genres = $this->tmdbApi->getMovieGenres();
        $userFavorites = $this->getUserFavorites();

        error_log("Rendering movies view");
        $view = new View();
        $view->render('movies', [
            'title' => 'Films',
            'movies' => $movies['results'],
            'genres' => $genres['genres'],
            'currentGenre' => $genre,
            'currentYear' => $year,
            'currentSort' => $sort,
            'userFavorites' => $userFavorites
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