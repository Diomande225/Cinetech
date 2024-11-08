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
//require_once 'controllers/ApiController.php';//
require_once 'controllers/FavoritesController.php';

// Récupérer l'URL demandée
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/Cinetech';
$path = str_replace($base_path, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

// Debug
error_log("Requested Path: " . $path);

// Routes
try {
    $auth = new AuthController();
    
    switch ($path) {
        case '/':
            $home = new HomeController();
            $home->index();
            break;
            
        case '/register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth->register();
            } else {
                $auth->showRegisterForm();
            }
            break;
            
        case '/login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth->login();
            } else {
                $auth->showLoginForm();
            }
            break;
            
        case '/favorites':
            $favorites = new FavoritesController();
            $favorites->index();
            break;
            
        case '/Cinetech/favorites':
            $favorites = new FavoritesController();
            $favorites->index();
            break;
            
        case '/Cinetech/api/favorites/toggle':
            $favoritesController = new FavoritesController();
            $favoritesController->toggle();
            exit;
            break;
            
        default:
            require 'views/404.php';
            break;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    require 'views/404.php';
}
?>
