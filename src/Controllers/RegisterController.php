<?php
namespace App\Controllers;

use App\Views\View;
use App\Models\User;

class RegisterController {
    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Vérifier si l'utilisateur existe déjà
            if (!User::findByEmail($email)) {
                // Créer un nouvel utilisateur
                User::create($username, $email, $password);
                // Rediriger vers la page de connexion après l'inscription réussie
                header('Location: /Cinetech/login');
                exit;
            } else {
                // Gérer le cas où l'email est déjà enregistré
                $error = "Cet email est déjà enregistré.";
            }
        }

        $view = new View();
        $view->render('register', [
            'title' => 'Inscription',
            'error' => $error ?? null
        ]);
    }
}