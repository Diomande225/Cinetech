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
            $movie = $this->tmdb->getMovieDetails($id);
            $trailer = $this->tmdb->getMovieTrailer($id);
            $credits = $this->tmdb->getMovieCredits($id);
            require 'views/movies/details.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            require 'views/404.php';
        }
    }
} 