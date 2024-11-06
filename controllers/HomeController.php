<?php
class HomeController {
    private $api_key = 'c21ac6ce8a090027847698c1f58d5a71';

    private function fetchTMDBData($endpoint) {
        try {
            $url = "https://api.themoviedb.org/3/{$endpoint}&api_key={$this->api_key}&language=fr-FR";
            $response = file_get_contents($url);
            return json_decode($response, true);
        } catch (Exception $e) {
            return ['results' => []];
        }
    }

    public function index() {
        // Récupérer un film populaire aléatoire pour le banner
        $popularMovies = $this->fetchTMDBData('trending/movie/week?');
        $randomIndex = array_rand($popularMovies['results']);
        $featuredMovie = $popularMovies['results'][$randomIndex];
        
        // Récupérer les détails complets du film
        $movieDetails = $this->fetchTMDBData("movie/{$featuredMovie['id']}?");
        
        // Récupérer différentes catégories
        $categories = [
            'trending' => $this->fetchTMDBData('trending/all/day?'),
            'popular_movies' => $this->fetchTMDBData('movie/popular?'),
            'top_rated' => $this->fetchTMDBData('movie/top_rated?'),
            'upcoming' => $this->fetchTMDBData('movie/upcoming?'),
            'popular_tv' => $this->fetchTMDBData('tv/popular?')
        ];

        require 'views/home.php';
    }
} 