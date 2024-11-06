<?php
class MovieController {
    private $api_key = 'c21ac6ce8a090027847698c1f58d5a71';
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function show($id) {
        $movie = $this->fetchMovieDetails($id);
        $videos = $this->fetchMovieVideos($id);
        $similar = $this->fetchSimilarMovies($id);
        $isFavorite = $this->isInFavorites($id);
        
        require 'views/movies/show.php';
    }

    private function fetchMovieDetails($id) {
        $url = "https://api.themoviedb.org/3/movie/{$id}?api_key={$this->api_key}&language=fr-FR&append_to_response=credits";
        return json_decode(file_get_contents($url), true);
    }

    private function fetchMovieVideos($id) {
        $url = "https://api.themoviedb.org/3/movie/{$id}/videos?api_key={$this->api_key}&language=fr-FR";
        $response = json_decode(file_get_contents($url), true);
        return array_filter($response['results'], function($video) {
            return $video['type'] === 'Trailer' && $video['site'] === 'YouTube';
        });
    }

    private function fetchSimilarMovies($id) {
        $url = "https://api.themoviedb.org/3/movie/{$id}/similar?api_key={$this->api_key}&language=fr-FR";
        return json_decode(file_get_contents($url), true)['results'];
    }

    private function isInFavorites($movieId) {
        if (!isset($_SESSION['user'])) return false;
        
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$_SESSION['user']['id'], $movieId]);
        return $stmt->fetch() !== false;
    }

    public function toggleFavorite() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        $movieId = $_POST['movie_id'];
        $userId = $_SESSION['user']['id'];

        if ($this->isInFavorites($movieId)) {
            $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND movie_id = ?");
            $stmt->execute([$userId, $movieId]);
            echo json_encode(['status' => 'removed']);
        } else {
            $stmt = $this->db->prepare("INSERT INTO favorites (user_id, movie_id) VALUES (?, ?)");
            $stmt->execute([$userId, $movieId]);
            echo json_encode(['status' => 'added']);
        }
    }
} 