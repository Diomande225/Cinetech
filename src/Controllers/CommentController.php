<?php
namespace App\Controllers;

use App\Models\CommentModel;
use App\Views\View;

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function addComment() {
        error_log("=== Début addComment ===");
        error_log("Méthode HTTP: " . $_SERVER['REQUEST_METHOD']);
        error_log("Content-Type: " . $_SERVER['CONTENT_TYPE']);
        
        header('Content-Type: application/json');
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            error_log("Session ID: " . session_id());
            error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'non défini'));
            
            if (!isset($_SESSION['user_id'])) {
                throw new \Exception('Utilisateur non connecté');
            }

            $rawData = file_get_contents('php://input');
            error_log("Données brutes reçues: " . $rawData);
            
            $data = json_decode($rawData, true);
            error_log("Données décodées: " . print_r($data, true));

            if (!$data || empty($data['content']) || empty($data['item_id']) || empty($data['item_type'])) {
                throw new \Exception('Données manquantes');
            }

            $commentId = $this->commentModel->addComment(
                $_SESSION['user_id'],
                $data['item_id'],
                $data['item_type'],
                trim($data['content'])
            );

            if (!$commentId) {
                throw new \Exception('Erreur lors de l\'ajout du commentaire');
            }

            $comment = $this->commentModel->getCommentById($commentId);
            error_log("Commentaire créé: " . print_r($comment, true));

            if (!$comment) {
                throw new \Exception('Commentaire non trouvé après création');
            }

            echo json_encode([
                'status' => 'success',
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCommentsHtml($itemId, $mediaType) {
        error_log("getCommentsHtml appelé avec itemId: $itemId, mediaType: $mediaType");
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Récupérer les commentaires
            $comments = $this->commentModel->getComments($itemId, $mediaType);
            error_log("Nombre de commentaires récupérés : " . count($comments));

            // Préparer les variables pour le template
            $data = [
                'comments' => $comments,
                'itemId' => $itemId,
                'itemType' => $mediaType,
                'user_id' => $_SESSION['user_id'] ?? null
            ];

            // Extraire les variables pour le template
            extract($data);
            
            ob_start();
            include __DIR__ . '/../Views/templates/comments.php';
            return ob_get_clean();
            
        } catch (\Exception $e) {
            error_log("Erreur dans getCommentsHtml: " . $e->getMessage());
            return "Erreur lors du chargement des commentaires";
        }
    }

    public function showAllComments($mediaType, $itemId) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $comments = $this->commentModel->getComments($itemId, $mediaType);
            
            $view = new View();
            $view->render('allComments', [
                'title' => 'Tous les commentaires',
                'comments' => $comments,
                'mediaType' => $mediaType,
                'itemId' => $itemId
            ]);
        } catch (\Exception $e) {
            error_log("Erreur dans showAllComments: " . $e->getMessage());
        }
    }

    public function deleteComment() {
        header('Content-Type: application/json');
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                throw new \Exception('Utilisateur non connecté');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['comment_id'])) {
                throw new \Exception('ID du commentaire manquant');
            }

            // Vérifier que l'utilisateur est propriétaire du commentaire
            $comment = $this->commentModel->getCommentById($data['comment_id']);
            if (!$comment || $comment['user_id'] !== $_SESSION['user_id']) {
                throw new \Exception('Vous n\'êtes pas autorisé à supprimer ce commentaire');
            }

            $success = $this->commentModel->deleteComment($data['comment_id']);
            if (!$success) {
                throw new \Exception('Erreur lors de la suppression du commentaire');
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Commentaire supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}