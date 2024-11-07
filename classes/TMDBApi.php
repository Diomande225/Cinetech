<?php

class TMDBApi {
    private $api_key;
    private $base_url = 'https://api.themoviedb.org/3';
    private $language = 'fr-FR';

    public function __construct() {
        $this->api_key = 'c21ac6ce8a090027847698c1f58d5a71';
    }

    // Méthode générique pour les appels API
    public function get($endpoint, $params = []) {
        try {
            $defaultParams = [
                'api_key' => $this->api_key,
                'language' => $this->language
            ];
            $params = array_merge($defaultParams, $params);
            
            $url = $this->base_url . $endpoint . '?' . http_build_query($params);
            $response = file_get_contents($url);
            
            if ($response === false) {
                throw new Exception('Impossible de récupérer les données depuis l\'API TMDB');
            }
            
            return json_decode($response, true);
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    // Films
    public function getTrending($timeWindow = 'week', $page = 1) {
        return $this->get("/trending/all/{$timeWindow}", ['page' => $page]);
    }

    public function getPopularMovies($page = 1) {
        return $this->get('/movie/popular', ['page' => $page]);
    }

    public function getTopRatedMovies($page = 1) {
        return $this->get('/movie/top_rated', ['page' => $page]);
    }

    public function getUpcomingMovies($page = 1) {
        return $this->get('/movie/upcoming', ['page' => $page]);
    }

    public function getNowPlayingMovies($page = 1) {
        return $this->get('/movie/now_playing', ['page' => $page]);
    }

    // Séries TV
    public function getPopularTVShows($page = 1) {
        return $this->get('/tv/popular', ['page' => $page]);
    }

    public function getTopRatedTVShows($page = 1) {
        return $this->get('/tv/top_rated', ['page' => $page]);
    }

    public function getAiringTodayTVShows($page = 1) {
        return $this->get('/tv/airing_today', ['page' => $page]);
    }

    public function getOnTheAirTVShows($page = 1) {
        return $this->get('/tv/on_the_air', ['page' => $page]);
    }

    // Détails des médias
    public function getMovieDetails($id) {
        return $this->get("/movie/{$id}", [
            'append_to_response' => 'videos,credits,similar,recommendations,keywords'
        ]);
    }

    public function getTVShowDetails($id) {
        return $this->get("/tv/{$id}", [
            'append_to_response' => 'videos,credits,similar,recommendations,keywords,seasons'
        ]);
    }

    // Découverte
    public function discoverMovies($params = []) {
        return $this->get('/discover/movie', $params);
    }

    public function discoverTVShows($params = []) {
        return $this->get('/discover/tv', $params);
    }

    // Genres
    public function getMovieGenres() {
        return $this->get('/genre/movie/list');
    }

    public function getTVGenres() {
        return $this->get('/genre/tv/list');
    }

    // Recherche
    public function search($query, $page = 1) {
        return $this->get('/search/multi', [
            'query' => $query,
            'page' => $page
        ]);
    }

    // Saisons et épisodes
    public function getSeasonDetails($tvId, $seasonNumber) {
        return $this->get("/tv/{$tvId}/season/{$seasonNumber}");
    }

    public function getEpisodeDetails($tvId, $seasonNumber, $episodeNumber) {
        return $this->get("/tv/{$tvId}/season/{$seasonNumber}/episode/{$episodeNumber}");
    }

    // Recommandations
    public function getMovieRecommendations($movieId, $page = 1) {
        return $this->get("/movie/{$movieId}/recommendations", ['page' => $page]);
    }

    public function getTVShowRecommendations($tvId, $page = 1) {
        return $this->get("/tv/{$tvId}/recommendations", ['page' => $page]);
    }

    // Tendances par catégorie
    public function getTrendingMovies($timeWindow = 'week') {
        return $this->get("/trending/movie/{$timeWindow}");
    }

    public function getTrendingTVShows($timeWindow = 'week') {
        return $this->get("/trending/tv/{$timeWindow}");
    }
} 