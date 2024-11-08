<?php
require_once 'classes/TMDBApi.php';
require_once 'config/database.php';

class TVShowController {
    private $tmdb;
    private $db;

    public function __construct() {
        $this->tmdb = new TMDBApi();
        $this->db = $db;
    }

    public function index() {
        try {
            $shows = [
                'popular' => $this->tmdb->getPopularTVShows(),
                'top_rated' => $this->tmdb->getTopRatedTVShows(),
                'airing_today' => $this->tmdb->getAiringTodayTVShows()
            ];
            require 'views/tv-shows/index.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            require 'views/404.php';
        }
    }

    public function show($id) {
        try {
            $show = $this->tmdb->getTVShowDetails($id);
            $trailer = $this->tmdb->getTVShowTrailer($id);
            $credits = $this->tmdb->getTVShowCredits($id);
            require 'views/tv-shows/details.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            require 'views/404.php';
        }
    }

    private function getComments($showId) {
        $stmt = $this->db->prepare("SELECT comments.*, users.username 
                             FROM tv_comments comments 
                             JOIN users ON comments.user_id = users.id 
                             WHERE show_id = ? 
                             ORDER BY created_at DESC");
        $stmt->execute([$showId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getUserRating($showId) {
        if (!isset($_SESSION['user'])) return null;
        
        $stmt = $this->db->prepare("SELECT rating FROM tv_ratings WHERE user_id = ? AND show_id = ?");
        $stmt->execute([$_SESSION['user']['id'], $showId]);
        return $stmt->fetchColumn();
    }

    private function isInFavorites($showId) {
        if (!isset($_SESSION['user'])) return false;
        
        $stmt = $this->db->prepare("SELECT id FROM tv_favorites WHERE user_id = ? AND show_id = ?");
        $stmt->execute([$_SESSION['user']['id'], $showId]);
        return $stmt->fetch() !== false;
    }

    public function addComment() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $showId = $_POST['show_id'];
            $comment = $_POST['comment'];
            $userId = $_SESSION['user']['id'];

            $stmt = $this->db->prepare("INSERT INTO tv_comments (user_id, show_id, content, created_at) 
                                 VALUES (?, ?, ?, NOW())");
            $stmt->execute([$userId, $showId, $comment]);

            header("Location: /tv/{$showId}");
        }
    }
} 