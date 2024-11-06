<?php
class TVShowController {
    private $tmdb;
    private $tvShowModel;

    public function __construct() {
        $this->tmdb = new TMDBApi();
        $this->tvShowModel = new TVShowModel();
    }

    public function index() {
        $page = $_GET['page'] ?? 1;
        $shows = $this->tmdb->getPopularTVShows($page);
        
        require 'views/tv-shows/index.php';
    }

    public function show($id) {
        $show = $this->tmdb->getTVShowDetails($id);
        $comments = $this->tvShowModel->getComments($id);
        $isFavorite = isAuthenticated() ? 
            $this->tvShowModel->isFavorite($id, getCurrentUser()['id']) : false;
        
        require 'views/tv-shows/details.php';
    }
} 