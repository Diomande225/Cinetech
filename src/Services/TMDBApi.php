<?php
namespace App\Services;

class TMDBApi {
    private $apiKey;
    private $baseUrl;
    private $imageBaseUrl;

    public function __construct() {
        $path = __DIR__ . '/../../config/config.php';
        if (!file_exists($path)) {
            throw new \Exception("Fichier config.php introuvable au chemin : " . $path);
        }

        $config = require $path;

        $this->apiKey = $config['tmdb']['api_key'];
        $this->baseUrl = $config['tmdb']['base_url'];
        $this->imageBaseUrl = $config['tmdb']['image_base_url'];
    }

    public function fetchTrending() {
        $url = $this->baseUrl . '/trending/all/week?api_key=' . $this->apiKey;
        return $this->makeRequest($url);
    }

    public function fetchPopularMovies() {
        $url = $this->baseUrl . '/movie/popular?api_key=' . $this->apiKey;
        return $this->makeRequest($url);
    }

    public function fetchPopularSeries() {
        $url = $this->baseUrl . '/tv/popular?api_key=' . $this->apiKey;
        return $this->makeRequest($url);
    }

    private function makeRequest($url) {
        $response = file_get_contents($url);
        if ($response === false) {
            throw new \Exception("Failed to fetch data from TMDB API");
        }
        return json_decode($response, true);
    }

    public function getImageUrl($path, $size = 'w500') {
        return $this->imageBaseUrl . $size . $path;
    }

    public function fetchTrendingTv() {
        $url = $this->baseUrl . '/trending/tv/week?api_key=' . $this->apiKey;
        return $this->makeRequest($url);
    }
    
    public function fetchTrendingMovies() {
        $url = $this->baseUrl . '/trending/movie/week?api_key=' . $this->apiKey;
        return $this->makeRequest($url);
    }

    public function fetchMovieDetails($id) {
        $url = $this->baseUrl . "/movie/{$id}?api_key=" . $this->apiKey . "&append_to_response=credits,videos";
        return $this->makeRequest($url);
    }
    
    public function fetchTvDetails($id) {
        $url = $this->baseUrl . "/tv/{$id}?api_key=" . $this->apiKey . "&append_to_response=credits,videos";
        return $this->makeRequest($url);
    }
    
    public function fetchMovieCredits($id) {
        $url = $this->baseUrl . "/movie/{$id}/credits?api_key=" . $this->apiKey;
        return $this->makeRequest($url);
    }
    
    public function fetchTvCredits($id) {
        
       

}

public function getTvSeries($genre = null, $year = null, $sort = 'popularity.desc') {
$url = $this->baseUrl . "/discover/tv?api_key=" . $this->apiKey . "&sort_by=" . $sort;
if ($genre) {
$url .= "&with_genres=" . $genre;
}
if ($year) {
$url .= "&first_air_date_year=" . $year;
}
return $this->makeRequest($url);
}

public function getTvGenres() {
$url = $this->baseUrl . "/genre/tv/list?api_key=" . $this->apiKey;
return $this->makeRequest($url);
}

public function getMovies($genre = null, $year = null, $sort = 'popularity.desc') {
    // Méthode pour récupérer les films avec des filtres
    $url = $this->baseUrl . "/discover/movie?api_key=" . $this->apiKey . "&sort_by=" . $sort;
    
    if ($genre) {
        $url .= "&with_genres=" . urlencode($genre); // Assurez-vous que le genre est bien encodé
    }
    
    if ($year) {
        $url .= "&primary_release_year=" . urlencode($year); // Assurez-vous que l'année est bien encodée
    }
    
    return $this->makeRequest($url); // Retourne la réponse de l'API
}

public function getMovieGenres() {
    // Méthode pour récupérer les genres de films
    $url = $this->baseUrl . "/genre/movie/list?api_key=" . $this->apiKey;
    return $this->makeRequest($url);
}

public function fetchActorDetails($id) {
    $url = $this->baseUrl . "/person/{$id}?api_key=" . $this->apiKey;
    return $this->makeRequest($url);
}

public function fetchActorCredits($id) {
    $url = $this->baseUrl . "/person/{$id}/combined_credits?api_key=" . $this->apiKey;
    return $this->makeRequest($url);
}

public function searchMulti($query) {
    $url = $this->baseUrl . "/search/multi?api_key=" . $this->apiKey . "&query=" . urlencode($query);
    $results = $this->makeRequest($url);
    
    // Debugging: Affichez les résultats de l'API
    error_log(print_r($results, true)); // Cela enverra les résultats dans le log PHP

    return $results;
}
}