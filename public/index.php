<?php
// Charge l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Import des classes nécessaires
use App\Config\Router;
use App\Controllers\HomeController;
use App\Controllers\MovieController;
use App\Controllers\UserController;

// Démarre la session
session_start();

// Crée une instance du router
$router = new Router();

// Définit toutes les routes de l'application
$router->add('GET', '/', HomeController::class, 'index');
$router->add('GET', '/movies', MovieController::class, 'index');
$router->add('GET', '/movie/{id}', MovieController::class, 'show');
$router->add('GET', '/login', UserController::class, 'login');
$router->add('POST', '/login', UserController::class, 'loginPost');

// Traite la requête actuelle
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']); 