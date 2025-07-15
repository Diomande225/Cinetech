<?php
namespace App\Controllers;

use App\Views\View;
use App\Services\TMDBApi;
use App\Models\FavorisModel;
use App\Database\Connection;

class HomeController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    public function index() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $trendingItems = $this->tmdbApi->fetchTrending();
        $popularMovies = $this->tmdbApi->fetchPopularMovies();
        $popularSeries = $this->tmdbApi->fetchPopularSeries();

        $userFavorites = $this->getUserFavorites();

        $view = new View();
        $view->render('home', [
            'title' => 'Bienvenue sur Cinetech',
            'trendingItems' => $trendingItems['results'] ?? [],
            'popularMovies' => $popularMovies['results'] ?? [],
            'popularSeries' => $popularSeries['results'] ?? [],
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
}