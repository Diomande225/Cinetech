<?php
class TMDBApi {
    private $api_key;
    private $base_url = 'https://api.themoviedb.org/3';
    private $language = 'fr-FR';

    public function __construct() {
        $this->api_key = 'c21ac6ce8a090027847698c1f58d5a71';
    }

    public function get($endpoint, $params = []) {
        $params['api_key'] = $this->api_key;
        $params['language'] = $this->language;

        $url = $this->base_url . $endpoint . '?' . http_build_query($params);

        $response = @file_get_contents($url);
        if ($response === false) {
            throw new Exception('Impossible de récupérer les données de TMDB');
        }

        return json_decode($response, true);
    }

    public function getShowDetails($id) {
        return $this->get("/tv/{$id}", ['append_to_response' => 'credits,videos,similar']);
    }

    public function getPopularShows($page = 1) {
        try {
            return $this->get('/tv/popular', ['page' => $page]);
        } catch (Exception $e) {
            // Log l'erreur si nécessaire
            return [
                'results' => [],
                'page' => 1,
                'total_pages' => 1
            ];
        }
    }

    public function getShowVideos($id) {
        return $this->get("/tv/{$id}/videos");
    }

    public function getSimilarShows($id) {
        return $this->get("/tv/{$id}/similar");
    }

    public function getShowCredits($id) {
        return $this->get("/tv/{$id}/credits");
    }

    public function searchShows($query, $page = 1) {
        return $this->get('/search/tv', [
            'query' => $query,
            'page' => $page
        ]);
    }
} 