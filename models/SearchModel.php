<?php
class SearchModel {
    private $db;
    private $tmdb;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->tmdb = new TMDBApi();
    }

    public function search($query, $page = 1) {
        return $this->tmdb->search($query, $page);
    }

    public function saveSearchHistory($userId, $query) {
        $query = "INSERT INTO search_history (user_id, search_query, created_at) 
                 VALUES (:user_id, :search_query, NOW())";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $userId,
            'search_query' => $query
        ]);
    }

    public function getSearchHistory($userId, $limit = 10) {
        $query = "SELECT * FROM search_history 
                 WHERE user_id = :user_id 
                 ORDER BY created_at DESC 
                 LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 