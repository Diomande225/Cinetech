<?php
class AuthService {
    private $db;
    private $userModel;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->userModel = new UserModel();
    }

    public function authenticate($email, $password) {
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        // Créer une session
        $this->createUserSession($user);
        return true;
    }

    public function register($data) {
        // Validation des données
        $errors = $this->validateRegistrationData($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash du mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Création de l'utilisateur
        $userId = $this->userModel->create($data);
        if (!$userId) {
            return ['success' => false, 'errors' => ['Erreur lors de la création du compte']];
        }

        return ['success' => true, 'user_id' => $userId];
    }

    private function validateRegistrationData($data) {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = "Le nom d'utilisateur est requis";
        } elseif ($this->userModel->usernameExists($data['username'])) {
            $errors[] = "Ce nom d'utilisateur existe déjà";
        }

        if (empty($data['email'])) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors[] = "Cet email existe déjà";
        }

        if (empty($data['password'])) {
            $errors[] = "Le mot de passe est requis";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }

        return $errors;
    }

    private function createUserSession($user) {
        // Retirer le mot de passe des données de session
        unset($user['password']);
        $_SESSION['user'] = $user;
        
        // Générer un nouveau token de session
        session_regenerate_id(true);
    }

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
    }
} 