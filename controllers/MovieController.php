<?php
require_once 'classes/TMDBApi.php';

class MovieController {
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        $movies = [
            'popular' => $this->tmdb->getPopularMovies(),
            'top_rated' => $this->tmdb->getTopRatedMovies(),
            'upcoming' => $this->tmdb->getUpcomingMovies()
        ];
        require 'views/movies/index.php';
    }

    public function show($id) {
        try {
            error_log("Fetching details for movie ID: " . $id);
            $movie = $this->tmdb->getMovieDetails($id);
            error_log("Movie details: " . print_r($movie, true));
            $trailer = $this->tmdb->getMovieTrailer($id);
            $credits = $this->tmdb->getMovieCredits($id);
            require 'views/movies/details.php';
        } catch (Exception $e) {
            error_log("Error fetching movie details: " . $e->getMessage());
            require 'views/404.php';
        }
    }
} 