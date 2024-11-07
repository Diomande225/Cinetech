<?php
require_once 'classes/TMDBApi.php';

class MovieController {
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TMDBApi();
    }

    public function show($id) {
        try {
            // Récupérer les détails du film
            $movie = $this->tmdb->getMovieDetails($id);
            
            // Récupérer la bande-annonce
            $videos = $this->tmdb->getMovieVideos($id);
            $trailer = null;
            
            if (!empty($videos['results'])) {
                foreach ($videos['results'] as $video) {
                    if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                        $trailer = $video;
                        break;
                    }
                }
            }

            // Charger la vue
            require 'views/movies/show.php';
        } catch (Exception $e) {
            // Log l'erreur
            error_log($e->getMessage());
            require 'views/404.php';
        }
    }
} 