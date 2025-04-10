<?php
namespace App\Controllers;

use App\Views\View;
use App\Services\TMDBApi;

class DetailController {
    private $tmdbApi;

    public function __construct() {
        $this->tmdbApi = new TMDBApi();
    }

    public function show($mediaType, $id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($mediaType === 'movie') {
            $details = $this->tmdbApi->fetchMovieDetails($id);
        } else if ($mediaType === 'tv') {
            $details = $this->tmdbApi->fetchTvDetails($id);
        } else {
            header('Location: /404');
            exit;
        }

        $view = new View();
        $view->render('detail', [
            'title' => $details['title'] ?? $details['name'],
            'details' => $details,
            'mediaType' => $mediaType,
            'item' => [
                'id' => $id,
                'media_type' => $mediaType
            ]
        ]);
    }

    public function actor($id) {
        $actorDetails = $this->tmdbApi->fetchActorDetails($id);
        $actorCredits = $this->tmdbApi->fetchActorCredits($id);

        $view = new View();
        $view->render('actor', [
            'title' => $actorDetails['name'],
            'actor' => $actorDetails,
            'credits' => $actorCredits
        ]);
    }
}