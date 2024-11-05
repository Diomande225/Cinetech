<?php
namespace App\Config;

class Router {
    // Stocke toutes les routes de l'application
    private $routes = [];

    // Ajoute une nouvelle route
    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,      // GET, POST, etc.
            'path' => $path,          // URL (ex: /movies)
            'controller' => $controller, // Classe du contrôleur
            'action' => $action       // Méthode à appeler
        ];
    }

    // Dirige la requête vers le bon contrôleur
    public function dispatch($requestUri, $requestMethod) {
        // Extrait le chemin de l'URL
        $uri = parse_url($requestUri, PHP_URL_PATH);
        
        // Parcourt toutes les routes enregistrées
        foreach ($this->routes as $route) {
            // Si une route correspond
            if ($route['path'] === $uri && $route['method'] === $requestMethod) {
                // Crée une instance du contrôleur et appelle l'action
                $controller = new $route['controller']();
                return $controller->{$route['action']}();
            }
        }
        
        // Si aucune route ne correspond
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
    }
}