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
} 