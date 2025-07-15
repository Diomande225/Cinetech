<?php
namespace App;

class Router {
    private $routes = [];

    public function add($path, $controller, $action) {
        $this->routes[$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($url) {
        error_log("Router::dispatch() called with URL: " . $url);
        error_log("Available routes: " . print_r(array_keys($this->routes), true));
        
        // Supprimer le préfixe /Cinetech si présent
        $url = str_replace('/Cinetech', '', $url);
        $url = trim($url, '/');
        
        // Logging pour les requêtes AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($isAjax) {
            error_log("AJAX request detected: " . $url);
        }
        
        // Si l'URL est vide, rediriger vers la page d'accueil
        if ($url === '') {
            error_log("Empty URL, redirecting to home");
            $controller = $this->routes['home']['controller'];
            $action = $this->routes['home']['action'];
            $controllerInstance = new $controller();
            $controllerInstance->$action();
            return;
        }
        
        // Recherche spécifique pour les requêtes de recherche
        if (strpos($url, 'search/autocomplete') !== false) {
            error_log("Search autocomplete route detected: " . $url);
            $controller = $this->routes['search/autocomplete']['controller'];
            $action = $this->routes['search/autocomplete']['action'];
            $controllerInstance = new $controller();
            $controllerInstance->$action();
            return;
        }

        // D'abord, essayer les routes exactes
        if (array_key_exists($url, $this->routes)) {
            error_log("Exact route match found for: " . $url);
            $controller = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
            $controllerInstance = new $controller();
            $controllerInstance->$action();
            return;
        }

        // Ensuite, essayer les routes avec paramètres
        foreach ($this->routes as $route => $handler) {
            $pattern = preg_replace('/:(\w+)/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                error_log("Pattern route match found for: " . $url . " with pattern: " . $pattern);
                array_shift($matches);
                $controller = $handler['controller'];
                $action = $handler['action'];
                $controllerInstance = new $controller();
                call_user_func_array([$controllerInstance, $action], array_values($matches));
                return;
            }
        }

        error_log("No route found for URL: " . $url);
        
        // Pour les requêtes AJAX, renvoyer une erreur JSON
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['error' => 'Route not found', 'url' => $url]);
            return;
        }
        
        throw new \Exception("No route found for URL: $url");
    }

    public function match($url) {
        foreach ($this->routes as $route => $params) {
            // Convertir la route en expression régulière
            $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^/]++)', $route);
            $pattern = "#^{$pattern}$#i";
            
            if (preg_match($pattern, $url, $matches)) {
                // Extraire les paramètres
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params['params'][$key] = $value;
                    }
                }
                return $params;
            }
        }
        return false;
    }
}