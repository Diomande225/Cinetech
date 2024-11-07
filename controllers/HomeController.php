<?php
require_once 'classes/TMDBApi.php';
require_once 'includes/helpers.php';

class HomeController {
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        try {
            // Récupérer les contenus pour chaque section
            $content = [
                'trending' => $this->tmdb->getTrending('day')['results'],
                'popular_movies' => $this->tmdb->getPopularMovies()['results'],
                'popular_shows' => $this->tmdb->getPopularTVShows()['results'],
                'top_rated_movies' => $this->tmdb->getTopRatedMovies()['results'],
                'upcoming_movies' => $this->tmdb->getUpcomingMovies()['results'],
                'airing_today' => $this->tmdb->getAiringTodayTVShows()['results']
            ];

            // Sélectionner un film/série aléatoire pour le banner
            $featured = $this->getRandomFeaturedContent($content['trending']);

            require 'views/home.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            require 'views/404.php';
        }
    }

    private function getRandomFeaturedContent($items) {
        if (empty($items)) {
            return null;
        }

        $item = $items[array_rand($items)];
        $mediaType = $item['media_type'] ?? 'movie';
        
        try {
            // Récupérer les détails complets
            if ($mediaType === 'movie') {
                $details = $this->tmdb->getMovieDetails($item['id']);
            } else {
                $details = $this->tmdb->getTVShowDetails($item['id']);
            }

            // Récupérer la bande-annonce
            $videos = $this->tmdb->get("/{$mediaType}/{$item['id']}/videos")['results'] ?? [];
            $trailerKey = null;
            
            foreach ($videos as $video) {
                if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                    $trailerKey = $video['key'];
                    break;
                }
            }

            return array_merge($details, [
                'media_type' => $mediaType,
                'trailer_key' => $trailerKey
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $item;
        }
    }
}

class SomeOtherClass {
    // SomeOtherClass methods and properties here
} 