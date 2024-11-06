<?php
class SearchController {
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        $query = $_GET['q'] ?? '';
        $page = $_GET['page'] ?? 1;
        
        $results = [];
        if ($query) {
            $results = $this->tmdb->search($query, $page);
        }
        
        require 'views/search/results.php';
    }
} 