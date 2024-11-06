<?php
class TMDBApi {
    private $api_key;
    private $base_url = 'https://api.themoviedb.org/3';

    public function __construct() {
        $this->api_key = API_KEY;
    }

    private function makeRequest($endpoint, $params = []) {
        $params['api_key'] = $this->api_key;
        $params['language'] = 'fr-FR';
        
        $url = $this->base_url . $endpoint . '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public function getPopularMovies($page = 1) {
        return $this->makeRequest('/movie/popular', ['page' => $page]);
    }

    public function getPopularTVShows($page = 1) {
        return $this->makeRequest('/tv/popular', ['page' => $page]);
    }

    public function getMovieDetails($movieId) {
        return $this->makeRequest("/movie/{$movieId}", [
            'append_to_response' => 'credits,similar,videos'
        ]);
    }

    public function getTVShowDetails($tvId) {
        return $this->makeRequest("/tv/{$tvId}", [
            'append_to_response' => 'credits,similar,videos'
        ]);
    }

    public function search($query, $page = 1) {
        return $this->makeRequest('/search/multi', [
            'query' => $query,
            'page' => $page
        ]);
    }
} 