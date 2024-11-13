<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la configuration
require_once 'config/database.php';
require_once 'config/api_config.php';

// Inclure les autres fichiers nécessaires
require_once 'classes/Database.php';
require_once 'classes/TMDBApi.php';
require_once 'includes/helpers.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/MovieController.php';
require_once 'controllers/TVShowController.php';
require_once 'controllers/FavoritesController.php';
require_once 'controllers/SearchController.php';

// Récupérer l'URL demandée
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/Cinetech';
$path = str_replace($base_path, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

// Debug
error_log("Requested Path: " . $path);

// Routes dynamiques pour les détails des films et séries
if (preg_match('#^/movie/(\d+)$#', $path, $matches)) {
    $controller = new MovieController();
    return $controller->show($matches[1]);
}

if (preg_match('#^/tv-show/(\d+)$#', $path, $matches)) {
    $controller = new TVShowController();
    return $controller->show($matches[1]);
}

// Routes API
if (strpos($path, '/api/') === 0) {
    header('Content-Type: application/json');
    switch ($path) {
        case '/api/favorites/toggle':
            $controller = new FavoriteController();
            return $controller->toggle();
        case '/api/search':
            $controller = new SearchController();
            return $controller->search();
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Route non trouvée']);
            exit;
    }
}

// Routes standards
try {
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
            
        case '/login':
            $auth = new AuthController();
            $auth->login();
            break;
            
        case '/register':
            $auth = new AuthController();
            $auth->register();
            break;
            
        case '/logout':
            $auth = new AuthController();
            $auth->logout();
            break;
            
        case '/favorites':
            $favorites = new FavoriteController();
            $favorites->index();
            break;
            
        case '/profile':
            $user = new UserController();
            $user->profile();
            break;
            
        case '/search':
            $search = new SearchController();
            $search->index();
            break;
            
        default:
            http_response_code(404);
            require 'views/404.php';
            break;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    require 'views/404.php';
}
?>