<?php
class SearchController {
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TMDBApi();
    }

    public function index() {
        $query = $_GET['q'] ?? '';
        if (empty($query)) {
            $_SESSION['errors'] = ["La requête de recherche ne peut pas être vide"];
            header('Location: /');
            exit;
        }
        $page = $_GET['page'] ?? 1;
        
        $results = [];
        if ($query) {
            $results = $this->tmdb->search($query, $page);
        }
        
        require 'views/search/results.php';
    }
} 