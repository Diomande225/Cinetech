<?php
session_start();
require_once 'config/database.php';
require_once 'classes/TMDBApi.php';
require_once 'controllers/MovieController.php';
require_once 'controllers/TVShowController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/SearchController.php';
require_once 'controllers/FavoritesController.php';
require_once 'includes/helpers.php';

// Récupérer l'URL demandée
$uri = $_SERVER['REQUEST_URI'];

// Si votre projet est dans un sous-dossier, retirez le nom du dossier de l'URI
$basePath = '/Cinetech';
$uri = str_replace($basePath, '', $uri);

// Système de routage
try {
    // Page d'accueil
    if ($uri === '' || $uri === '/') {
        $controller = new HomeController();
        $content = $controller->getPopularContent();
        require 'views/home.php';
        exit;
    }

    // Routes pour les films
    if ($uri === '/movies') {
        $controller = new MovieController();
        $controller->index();
        exit;
    }

    if (preg_match('#^/movie/(\d+)$#', $uri, $matches)) {
        $controller = new MovieController();
        $controller->show($matches[1]);
        exit;
    }

    // Routes pour les séries
    if ($uri === '/tv-shows') {
        $controller = new TVShowController();
        $controller->index();
        exit;
    }

    if (preg_match('#^/tv/(\d+)$#', $uri, $matches)) {
        $controller = new TVShowController();
        $controller->show($matches[1]);
        exit;
    }

    // Route de recherche
    if ($uri === '/search') {
        $controller = new SearchController();
        $controller->search();
        exit;
    }

    // Routes d'authentification
    if ($uri === '/login') {
        $controller = new AuthController();
        $controller->login();
        exit;
    }

    if ($uri === '/register') {
        $controller = new AuthController();
        $controller->register();
        exit;
    }

    if ($uri === '/logout') {
        $controller = new AuthController();
        $controller->logout();
        exit;
    }

    // Route des favoris
    if ($uri === '/favorites' && isset($_SESSION['user'])) {
        $controller = new UserController();
        $controller->favorites();
        exit;
    }

    // Route pour les favoris
    if ($uri === '/api/favorites/toggle' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new FavoritesController();
        $controller->toggle();
        exit;
    }

    // Si aucune route ne correspond
    throw new Exception('Page non trouvée');

} catch (Exception $e) {
    // Gérer les erreurs
    http_response_code(404);
    require 'views/404.php';
}
?>
