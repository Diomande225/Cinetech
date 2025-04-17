<?php
namespace App\Controllers;

use App\Services\TMDBApi;

class SearchController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    public function autocomplete() {
        // Ajouter les headers pour indiquer que c'est une réponse JSON
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $query = $_GET['query'] ?? '';

        if (strlen($query) >= 2) {
            $results = $this->tmdbApi->searchMulti($query);
            echo json_encode($results);
        } else {
            echo json_encode(['results' => []]);
        }
    }
}