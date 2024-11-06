<?php

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email)) {
                $errors['email'] = "L'email est requis";
            }
            if (empty($password)) {
                $errors['password'] = "Le mot de passe est requis";
            }

            if (empty($errors)) {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Connexion réussie
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email']
                    ];
                    header('Location: /');
                    exit;
                } else {
                    $errors['login'] = "Email ou mot de passe incorrect";
                }
            }
        }

        // Afficher la vue de connexion
        require_once 'views/auth/login.php';
    }

    public function register() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($username)) {
                $errors['username'] = "Le nom d'utilisateur est requis";
            }
            if (empty($email)) {
                $errors['email'] = "L'email est requis";
            }
            if (empty($password)) {
                $errors['password'] = "Le mot de passe est requis";
            }
            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = "Les mots de passe ne correspondent pas";
            }

            // Vérifier si l'email existe déjà
            if (empty($errors)) {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $errors['email'] = "Cet email est déjà utilisé";
                }
            }

            // Inscription si pas d'erreurs
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                
                try {
                    $stmt->execute([$username, $email, $hashedPassword]);
                    $_SESSION['success'] = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
                    header('Location: /login');
                    exit;
                } catch (PDOException $e) {
                    $errors['general'] = "Une erreur est survenue lors de l'inscription";
                }
            }
        }

        // Afficher la vue d'inscription
        require_once 'views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
} 