<?php
require_once 'classes/TMDBApi.php';
require_once 'config/database.php';

class MovieController {
    private $tmdb;
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            
            // Récupérer différentes catégories de films
            $movies = [
                'popular' => $this->tmdb->get('/movie/popular', ['page' => $page]),
                'top_rated' => $this->tmdb->get('/movie/top_rated')['results'],
                'upcoming' => $this->tmdb->get('/movie/upcoming')['results'],
                'now_playing' => $this->tmdb->get('/movie/now_playing')['results']
            ];

            require 'views/movies/index.php';
        } catch (Exception $e) {
            require 'views/404.php';
        }
    }

    public function show($id) {
        try {
            // Récupérer toutes les informations nécessaires
            $movieDetails = $this->tmdb->get("/movie/{$id}", [
                'append_to_response' => 'credits,videos,similar,recommendations,images'
            ]);

            // Récupérer la bande-annonce (en français si possible)
            $trailer = null;
            if (!empty($movieDetails['videos']['results'])) {
                foreach ($movieDetails['videos']['results'] as $video) {
                    if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                        if ($video['iso_639_1'] === 'fr') {
                            $trailer = $video;
                            break;
                        }
                        if (!$trailer) { // Prendre le premier trailer si pas de version française
                            $trailer = $video;
                        }
                    }
                }
            }

            // Formater le casting
            $cast = array_slice($movieDetails['credits']['cast'], 0, 10);
            $director = array_filter($movieDetails['credits']['crew'], function($person) {
                return $person['job'] === 'Director';
            });

            // Récupérer les commentaires
            $comments = $this->getComments($id);
            
            // Vérifier si le film est en favori
            $isFavorite = isset($_SESSION['user']) ? $this->isInFavorites($id) : false;

            // Récupérer la note de l'utilisateur
            $userRating = isset($_SESSION['user']) ? $this->getUserRating($id) : null;

            require 'views/movies/show.php';
        } catch (Exception $e) {
            require 'views/404.php';
        }
    }

    private function getComments($movieId) {
        $stmt = $this->db->prepare("SELECT comments.*, users.username 
                             FROM movie_comments comments 
                             JOIN users ON comments.user_id = users.id 
                             WHERE movie_id = ? 
                             ORDER BY created_at DESC");
        $stmt->execute([$movieId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getUserRating($movieId) {
        if (!isset($_SESSION['user'])) return null;
        
        $stmt = $this->db->prepare("SELECT rating FROM movie_ratings WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$_SESSION['user']['id'], $movieId]);
        return $stmt->fetchColumn();
    }

    private function isInFavorites($movieId) {
        if (!isset($_SESSION['user'])) return false;
        
        $stmt = $this->db->prepare("SELECT id FROM movie_favorites WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$_SESSION['user']['id'], $movieId]);
        return $stmt->fetch() !== false;
    }
} 