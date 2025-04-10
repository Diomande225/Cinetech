<?php
namespace App\Controllers;

use App\Services\TMDBApi;

class SearchController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    public function autocomplete() {
        $query = $_GET['query'] ?? '';

        if (strlen($query) >= 2) {
            $results = $this->tmdbApi->searchMulti($query);
            echo json_encode($results);
        } else {
            echo json_encode(['results' => []]);
        }
    }
}