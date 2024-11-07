<?php
require_once 'classes/TMDBApi.php';
require_once 'config/database.php';

class TVShowController {
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
            $shows = $this->tmdb->getPopularShows($page);
            $topRated = $this->tmdb->get('/tv/top_rated')['results'];
            $airingToday = $this->tmdb->get('/tv/airing_today')['results'];
            $onTheAir = $this->tmdb->get('/tv/on_the_air')['results'];
            
            require 'views/tv-shows/index.php';
        } catch (Exception $e) {
            require 'views/404.php';
        }
    }

    public function show($id) {
        try {
            $show = $this->tmdb->getTVShowDetails($id);
            $videos = $this->tmdb->getShowVideos($id)['results'];
            $similar = $this->tmdb->getSimilarShows($id)['results'];
            $comments = $this->getComments($id);
            $userRating = $this->getUserRating($id);
            $isFavorite = $this->isInFavorites($id);

            require 'views/tv-shows/show.php';
        } catch (Exception $e) {
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