<?php
namespace App\Controllers;

use App\Views\View;
use App\Models\User;

class LoginController {
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Vérifier si le compte est actif
                if (!$user['is_active']) {
                    $view = new View();
                    $view->render('Login', [
                        'title' => 'Connexion',
                        'error' => 'Ce compte a été désactivé. Veuillez contacter l\'administrateur.'
                    ]);
                    return;
                }
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: /Cinetech');
                exit;
            } else {
                $view = new View();
                $view->render('Login', [
                    'title' => 'Connexion',
                    'error' => 'Email ou mot de passe incorrect'
                ]);
                return;
            }
        }

        $view = new View();
        $view->render('login', [
            'title' => 'Connexion',
            'error' => null
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