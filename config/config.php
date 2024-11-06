<?php
// Configuration de l'environnement
define('ENV', 'development'); // ou 'production'

// Configuration de l'application
define('APP_NAME', 'La Cinétech');
define('APP_URL', 'http://localhost'); // Modifier selon votre environnement

// Configuration des sessions
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 jours
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();

// Gestion des erreurs
if (ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Fonctions d'autoload
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/',
        'models/',
        'services/'
    ];

    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Fonctions utilitaires globales
require_once __DIR__ . '/../utils/helpers.php'; 