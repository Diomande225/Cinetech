<?php
class Router {
    private $routes = [];
    private $api_key = 'c21ac6ce8a090027847698c1f58d5a71';

    public function __construct() {
        session_start();
        
        // Définition des routes principales
        $this->routes = [
            '/' => ['controller' => 'HomeController', 'action' => 'index'],
            '/movies' => ['controller' => 'MovieController', 'action' => 'index'],
            '/tv-shows' => ['controller' => 'TVShowController', 'action' => 'index'],
            '/login' => ['controller' => 'AuthController', 'action' => 'login'],
            '/register' => ['controller' => 'AuthController', 'action' => 'register'],
            '/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
            '/favorites' => ['controller' => 'FavoriteController', 'action' => 'index'],
            '/profile' => ['controller' => 'UserController', 'action' => 'profile'],
            '/search' => ['controller' => 'SearchController', 'action' => 'index'],
        ];
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = strtok($uri, '?');
        
        // Supprimer le chemin de base si nécessaire
        $basePath = '/Cinetech'; // Ajustez selon votre configuration
        $uri = str_replace($basePath, '', $uri);

        // Routes dynamiques pour les détails des films et séries
        if (preg_match('#^/movie/(\d+)$#', $uri, $matches)) {
            require_once 'controllers/MovieController.php';
            $controller = new MovieController();
            return $controller->show($matches[1]);
        }

        if (preg_match('#^/tv-show/(\d+)$#', $uri, $matches)) {
            require_once 'controllers/TVShowController.php';
            $controller = new TVShowController();
            return $controller->show($matches[1]);
        }

        // Routes API
        if (strpos($uri, '/api/') === 0) {
            header('Content-Type: application/json');
            return $this->handleApiRoute($uri);
        }

        // Routes standards
        if (isset($this->routes[$uri])) {
            $route = $this->routes[$uri];
            $controllerName = $route['controller'];
            $actionName = $route['action'];

            require_once "controllers/{$controllerName}.php";
            $controller = new $controllerName();
            return $controller->$actionName();
        }

        // Route 404 si aucune route ne correspond
        $this->handle404();
    }

    private function handleApiRoute($uri) {
        switch ($uri) {
            case '/api/favorites/toggle':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    return $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
                }
                require_once 'controllers/FavoriteController.php';
                $controller = new FavoriteController();
                return $controller->toggle();

            case '/api/search':
                if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                    return $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
                }
                require_once 'controllers/SearchController.php';
                $controller = new SearchController();
                return $controller->search();

            default:
                return $this->jsonResponse(['error' => 'Route non trouvée'], 404);
        }
    }

    private function handle404() {
        http_response_code(404);
        require_once 'views/404.php';
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data);
    }

    // Middleware pour vérifier l'authentification
    private function requireAuth() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}

// Utilisation dans index.php
require_once 'config/Database.php';
require_once 'config/api_config.php';

$router = new Router();
$router->dispatch();