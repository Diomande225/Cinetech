<?php
namespace App\Controllers;

use App\Services\TMDBApi;

class MovieController extends AbstractController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    // Page liste des films
    public function index() {
        $movies = $this->tmdbApi->getPopularMovies();
        
        $this->render('movies/index', [
            'movies' => $movies,
            'title' => 'Films populaires'
        ]);
    }

    // Page détail d'un film
    public function show($id) {
        $movie = $this->tmdbApi->getMovie($id);
        
        $this->render('movies/show', [
            'movie' => $movie,
            'title' => $movie['title']
        ]);
    }
}