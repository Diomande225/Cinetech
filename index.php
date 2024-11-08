<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la configuration
require_once 'config/database.php';

// Inclure les autres fichiers
require_once 'classes/Database.php';
require_once 'classes/TMDBApi.php';
require_once 'includes/helpers.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/MovieController.php';
require_once 'controllers/TVShowController.php';

// Récupérer l'URL demandée
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/Cinetech';
$path = str_replace($base_path, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

// Debug
error_log("Requested Path: " . $path);

// Routes dynamiques pour les détails des films et séries
if (preg_match('#^/movie/(\d+)$#', $path, $matches)) {
    require_once 'controllers/MovieController.php';
    $controller = new MovieController();
    return $controller->show($matches[1]);
}

if (preg_match('#^/tv-show/(\d+)$#', $path, $matches)) {
    require_once 'controllers/TVShowController.php';
    $controller = new TVShowController();
    return $controller->show($matches[1]);
}

// Routes
try {
    $auth = new AuthController();
    
    switch ($path) {
        case '/':
            $home = new HomeController();
            $home->index();
            break;
            
        case '/movies':
            $movies = new MovieController();
            $movies->index();
            break;
            
        case '/tv-shows':
            $tvShows = new TVShowController();
            $tvShows->index();
            break;
            
        // Ajoutez d'autres routes ici
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    require 'views/404.php';
}
?>
&   