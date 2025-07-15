<?php
namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\CommentModel;
use App\Models\User;

class AdminController extends BaseController {
    private $adminModel;
    private $commentModel;
    
    public function __construct() {
        parent::__construct();
        $this->adminModel = new AdminModel();
        $this->commentModel = new CommentModel();
    }
    
    /**
     * Affiche le tableau de bord d'administration
     */
    public function dashboard() {
        // Vérifier les droits d'admin
        if (!$this->isAdmin()) {
            header('Location: /Cinetech/login');
            exit;
        }
        
        // Récupérer les commentaires pour l'administration
        $comments = $this->commentModel->getAllCommentsWithDetails();
        
        // Récupérer tous les utilisateurs
        $users = User::getAllUsers();
        
        // Préparer les données pour la vue
        $viewData = [
            'title' => 'Tableau de bord d\'administration - Cinetech',
            'comments' => $comments,
            'users' => $users,
            'basePath' => '/Cinetech',
            'activeTab' => $_GET['tab'] ?? 'users'
        ];
        
        // Afficher la vue
        ob_start();
        include __DIR__ . '/../Views/admin/dashboard.php';
        $content = ob_get_clean();
        
        echo $content;
    }
    
    /**
     * Supprime un commentaire (appelé via AJAX)
     */
    public function deleteComment($commentId) {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Supprimer le commentaire
        $success = $this->commentModel->deleteComment($commentId);
        
        // Renvoyer la réponse
        $this->jsonResponse([
            'success' => $success,
            'message' => $success ? 'Commentaire supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }
    
    /**
     * Active un compte utilisateur
     */
    public function activateUser($userId) {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Activer l'utilisateur
        $success = User::activateUser($userId);
        
        // Renvoyer la réponse
        $this->jsonResponse([
            'success' => $success,
            'message' => $success ? 'Utilisateur activé avec succès' : 'Erreur lors de l\'activation'
        ]);
    }
    
    /**
     * Désactive un compte utilisateur
     */
    public function deactivateUser($userId) {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Désactiver l'utilisateur
        $success = User::deactivateUser($userId);
        
        // Renvoyer la réponse
        $this->jsonResponse([
            'success' => $success,
            'message' => $success ? 'Utilisateur désactivé avec succès' : 'Erreur lors de la désactivation'
        ]);
    }
    
    /**
     * Supprime un compte utilisateur
     */
    public function deleteUser($userId) {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Empêcher la suppression de son propre compte
        if ($userId == $_SESSION['user_id']) {
            $this->jsonResponse(['success' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte']);
            return;
        }
        
        // Supprimer l'utilisateur
        $success = User::deleteUser($userId);
        
        // Renvoyer la réponse
        $this->jsonResponse([
            'success' => $success,
            'message' => $success ? 'Utilisateur supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }
    
    /**
     * Vérifie si l'utilisateur courant est administrateur
     */
    private function isAdmin() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Vérifier s'il est administrateur
        return $this->adminModel->isUserAdmin($_SESSION['user_id']);
    }
    
    /**
     * Envoie une réponse JSON
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    /**
     * Récupère les détails d'un utilisateur pour l'édition
     */
    public function getUserDetails($userId) {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Récupérer les détails de l'utilisateur
        $user = User::findById($userId);
        
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
            return;
        }
        
        // Envoyer les données de l'utilisateur (sans mot de passe)
        unset($user['password']);
        $this->jsonResponse(['success' => true, 'user' => $user]);
    }
    
    /**
     * Met à jour les informations d'un utilisateur
     */
    public function updateUser($userId) {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Récupérer les données du formulaire
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        // Valider les données
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Le nom d\'utilisateur est requis';
        } elseif (User::usernameExists($username, $userId)) {
            $errors[] = 'Ce nom d\'utilisateur est déjà utilisé';
        }
        
        if (empty($email)) {
            $errors[] = 'L\'email est requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email n\'est pas valide';
        } elseif (User::emailExists($email, $userId)) {
            $errors[] = 'Cet email est déjà utilisé';
        }
        
        if (!empty($errors)) {
            $this->jsonResponse(['success' => false, 'errors' => $errors]);
            return;
        }
        
        // Mettre à jour l'utilisateur
        $updateData = [
            'username' => $username,
            'email' => $email
        ];
        
        $success = User::updateUser($userId, $updateData);
        
        if ($success) {
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => [
                    'id' => $userId,
                    'username' => $username,
                    'email' => $email
                ]
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
    
    /**
     * Crée un nouvel utilisateur
     */
    public function createUser() {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->jsonResponse(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        // Récupérer les données du formulaire
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        // Valider les données
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Le nom d\'utilisateur est requis';
        } elseif (User::usernameExists($username)) {
            $errors[] = 'Ce nom d\'utilisateur est déjà utilisé';
        }
        
        if (empty($email)) {
            $errors[] = 'L\'email est requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email n\'est pas valide';
        } elseif (User::emailExists($email)) {
            $errors[] = 'Cet email est déjà utilisé';
        }
        
        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        }
        
        if (!empty($errors)) {
            $this->jsonResponse(['success' => false, 'errors' => $errors]);
            return;
        }
        
        // Créer l'utilisateur
        $result = User::create($username, $email, $password);
        
        if ($result['success']) {
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Utilisateur créé avec succès',
                'user' => [
                    'id' => $result['user_id'],
                    'username' => $username,
                    'email' => $email,
                    'is_active' => 1,
                    'is_admin' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur: ' . ($result['error'] ?? 'Erreur inconnue')]);
        }
    }
} 