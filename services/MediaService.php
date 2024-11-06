<?php
class MediaService {
    private $tmdb;
    private $db;

    public function __construct() {
        $this->tmdb = new TMDBApi();
        $this->db = Database::getInstance();
    }

    public function getHomePageContent() {
        return [
            'trending' => $this->tmdb->getTrending(),
            'popular_movies' => $this->tmdb->getPopularMovies(),
            'popular_shows' => $this->tmdb->getPopularTVShows(),
            'upcoming' => $this->tmdb->getUpcoming()
        ];
    }

    public function getMediaDetails($id, $type) {
        $details = $type === 'movie' ? 
            $this->tmdb->getMovieDetails($id) : 
            $this->tmdb->getTVShowDetails($id);

        // Ajouter les informations locales (commentaires, notes, etc.)
        $details['local_data'] = $this->getLocalMediaData($id, $type);

        return $details;
    }

    private function getLocalMediaData($id, $type) {
        // Récupérer les commentaires
        $query = "SELECT c.*, u.username, u.avatar 
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.content_id = :content_id 
                 AND c.content_type = :content_type 
                 ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'content_id' => $id,
            'content_type' => $type
        ]);

        return [
            'comments' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'rating' => $this->getAverageRating($id, $type),
            'favorite_count' => $this->getFavoriteCount($id, $type)
        ];
    }

    private function getAverageRating($id, $type) {
        $query = "SELECT AVG(rating) as average 
                 FROM ratings 
                 WHERE content_id = :content_id 
                 AND content_type = :content_type";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'content_id' => $id,
            'content_type' => $type
        ]);

        return round($stmt->fetchColumn(), 1);
    }

    private function getFavoriteCount($id, $type) {
        $query = "SELECT COUNT(*) 
                 FROM favorites 
                 WHERE content_id = :content_id 
                 AND content_type = :content_type";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'content_id' => $id,
            'content_type' => $type
        ]);

        return $stmt->fetchColumn();
    }
} 