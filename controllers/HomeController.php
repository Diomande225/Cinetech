<?php
class HomeController {
    private $api_key = 'c21ac6ce8a090027847698c1f58d5a71';

    public function getPopularContent() {
        try {
            // Récupérer les films tendances pour le banner
            $trendingMovies = $this->fetchTMDBData('trending/movie/week');
            $randomMovie = $trendingMovies['results'][array_rand($trendingMovies['results'])];
            
            // Récupérer les détails complets du film en vedette
            $movieDetails = $this->fetchTMDBData("movie/{$randomMovie['id']}");
            
            // Récupérer les différentes catégories
            $categories = [
                'trending' => $this->fetchTMDBData('trending/all/day')['results'],
                'popular_movies' => $this->fetchTMDBData('movie/popular')['results'],
                'top_rated_movies' => $this->fetchTMDBData('movie/top_rated')['results'],
                'upcoming_movies' => $this->fetchTMDBData('movie/upcoming')['results'],
                'popular_tv' => $this->fetchTMDBData('tv/popular')['results'],
                'top_rated_tv' => $this->fetchTMDBData('tv/top_rated')['results']
            ];

            return [
                'movieDetails' => $movieDetails,
                'categories' => $categories,
                'success' => true
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function fetchTMDBData($endpoint) {
        $url = "https://api.themoviedb.org/3/{$endpoint}?api_key={$this->api_key}&language=fr-FR";
        $response = @file_get_contents($url);
        
        if ($response === false) {
            throw new Exception('Impossible de récupérer les données de TMDB');
        }
        
        return json_decode($response, true);
    }

    // Fonction utilitaire pour formater les dates
    private function formatDate($date) {
        return date('d/m/Y', strtotime($date));
    }
} 