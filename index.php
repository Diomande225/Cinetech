<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Lang/helpers.php';

use App\Router;
use App\Controllers\HomeController;
use App\Controllers\MoviesController;
use App\Controllers\TvseriesController;
use App\Controllers\FavorisController;
use App\Controllers\RegisterController;
use App\Controllers\LoginController;
use App\Controllers\DetailController;
use App\Controllers\SearchController;
use App\Controllers\ProfileController;
use App\Controllers\CommentController;
use App\Controllers\LanguageController;
use App\Controllers\AdminController;

$router = new Router();

// Définir le chemin de base
$basePath = '/Cinetech';

// Définir les routes
$router->add('home', HomeController::class, 'index');
$router->add('movies', MoviesController::class, 'films');
$router->add('tvseries', TvseriesController::class, 'series');
$router->add('favoris', FavorisController::class, 'favoris');
$router->add('login', LoginController::class, 'login');
$router->add('register', RegisterController::class, 'register');

// Routes pour les détails et la recherche
$router->add('detail/:mediaType/:id', DetailController::class, 'show');
$router->add('actor/:id', DetailController::class, 'actor');
$router->add('search/autocomplete', SearchController::class, 'autocomplete');

// Routes pour les favoris
$router->add('favoris/add', FavorisController::class, 'addFavori');
$router->add('favoris/remove', FavorisController::class, 'removeFavori');

// Routes pour les commentaires
$router->add('comments/:itemType/:itemId', CommentController::class, 'show');
$router->add('add-comment', CommentController::class, 'addComment');
$router->add('delete-comment', CommentController::class, 'deleteComment');

// Routes pour le profil et la déconnexion
$router->add('profile', ProfileController::class, 'show');
$router->add('logout', LoginController::class, 'logout');

// Routes pour voir tous les commentaires
$router->add('comments/:mediaType/:id', CommentController::class, 'showAllComments');
$router->add('allComments/:mediaType/:id', CommentController::class, 'showAllComments');

// Route pour changer la langue
$router->add('language/change/:lang', LanguageController::class, 'changeLanguage');

// Routes pour l'administration
$router->add('admin', AdminController::class, 'dashboard');
$router->add('admin/comments/delete/:id', AdminController::class, 'deleteComment');
$router->add('admin/users/activate/:id', AdminController::class, 'activateUser');
$router->add('admin/users/deactivate/:id', AdminController::class, 'deactivateUser');
$router->add('admin/users/delete/:id', AdminController::class, 'deleteUser');
$router->add('admin/users/details/:id', AdminController::class, 'getUserDetails');
$router->add('admin/users/update/:id', AdminController::class, 'updateUser');

// Obtenir le chemin URL actuel
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Debug
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Base Path: " . $basePath);
error_log("URL before processing: " . $url);

// Si l'URL commence par le chemin de base (insensible à la casse), le retirer
if (stripos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}

// Nettoyer l'URL
$url = trim($url, '/');

// Si l'URL est vide, rediriger vers home
if ($url === '') {
    $url = 'home';
}

error_log("Processed URL: " . $url);

// Dispatcher la route
try {
    $router->dispatch($url);
} catch (Exception $e) {
    error_log("Router error: " . $e->getMessage());
    http_response_code(404);
    echo "404 Non Trouvé : " . $e->getMessage();
}