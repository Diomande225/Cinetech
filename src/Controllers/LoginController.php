<?php
namespace App\Controllers;

use App\Views\View;
use App\Models\User;

class LoginController {
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Rediriger vers la page d'accueil après connexion réussie
                header('Location: /Cinetech/home');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }

        $view = new View();
        $view->render('login', [
            'title' => 'Connexion',
            'error' => $error
        ]);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /Cinetech/home');
        exit;
    }
}