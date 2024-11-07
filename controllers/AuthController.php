<?php

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function showLoginForm() {
        require 'views/auth/login.php';
    }

    public function showRegisterForm() {
        require 'views/auth/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Cinetech/register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];
        if (empty($username)) $errors[] = "Le nom d'utilisateur est requis";
        if (empty($email)) $errors[] = "L'email est requis";
        if (empty($password)) $errors[] = "Le mot de passe est requis";
        if ($password !== $confirmPassword) $errors[] = "Les mots de passe ne correspondent pas";

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /Cinetech/register');
            exit;
        }

        try {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            
            if ($stmt->fetch()) {
                $_SESSION['errors'] = ["Cet email ou nom d'utilisateur existe déjà"];
                header('Location: /Cinetech/register');
                exit;
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur
            $stmt = $this->db->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            $stmt->execute([$username, $email, $hashedPassword]);

            $_SESSION['success'] = "Compte créé avec succès !";
            header('Location: /Cinetech/login');
            exit;

        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['errors'] = ["Une erreur est survenue lors de l'inscription"];
            header('Location: /Cinetech/register');
            exit;
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ];
                header('Location: /');
                exit;
            }

            $_SESSION['errors'] = ["Email ou mot de passe incorrect"];
            header('Location: /login');
            exit;

        } catch (Exception $e) {
            $_SESSION['errors'] = ["Une erreur est survenue lors de la connexion"];
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
} 