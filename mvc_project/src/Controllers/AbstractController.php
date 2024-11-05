<?php
namespace App\Controllers;

abstract class AbstractController {
    // Méthode pour afficher une vue
    protected function render($view, $data = []) {
        // Convertit le tableau associatif en variables
        extract($data);
        
        // Démarre la mise en tampon de sortie
        ob_start();
        // Inclut la vue spécifique
        require_once __DIR__ . "/../Views/{$view}.php";
        // Récupère le contenu du tampon
        $content = ob_get_clean();
        
        // Inclut le layout principal
        require_once __DIR__ . "/../Views/layout/default.php";
    }

    // Méthode pour rediriger
    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
}