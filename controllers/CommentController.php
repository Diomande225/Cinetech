<?php
class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function add() {
        if (!isAuthenticated()) {
            http_response_code(401);
            return json_encode(['error' => 'Non autorisé']);
        }

        $contentId = $_POST['content_id'] ?? null;
        $contentType = $_POST['content_type'] ?? null;
        $comment = $_POST['comment'] ?? null;
        $parentId = $_POST['parent_id'] ?? null;

        if (!$contentId || !$contentType || !$comment) {
            http_response_code(400);
            return json_encode(['error' => 'Données manquantes']);
        }

        $userId = getCurrentUser()['id'];
        $success = $this->commentModel->addComment(
            $userId,
            $contentId,
            $contentType,
            $comment,
            $parentId
        );

        if ($success) {
            // Récupérer le commentaire nouvellement ajouté pour l'afficher
            $newComment = $this->commentModel->getComments($contentId, $contentType, 1)[0];
            return json_encode(['success' => true, 'comment' => $newComment]);
        }

        http_response_code(500);
        return json_encode(['error' => 'Erreur lors de l\'ajout du commentaire']);
    }

    public function delete($id) {
        if (!isAuthenticated()) {
            http_response_code(401);
            return json_encode(['error' => 'Non autorisé']);
        }

        $userId = getCurrentUser()['id'];
        $success = $this->commentModel->deleteComment($id, $userId);

        return json_encode(['success' => $success]);
    }

    public function edit($id) {
        if (!isAuthenticated()) {
            http_response_code(401);
            return json_encode(['error' => 'Non autorisé']);
        }

        $newText = $_POST['comment'] ?? null;
        if (!$newText) {
            http_response_code(400);
            return json_encode(['error' => 'Texte du commentaire manquant']);
        }

        $userId = getCurrentUser()['id'];
        $success = $this->commentModel->editComment($id, $userId, $newText);

        return json_encode(['success' => $success]);
    }

    public function load() {
        $contentId = $_GET['content_id'] ?? null;
        $contentType = $_GET['content_type'] ?? null;
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        if (!$contentId || !$contentType) {
            http_response_code(400);
            return json_encode(['error' => 'Paramètres manquants']);
        }

        $comments = $this->commentModel->getComments($contentId, $contentType, $limit, $offset);
        return json_encode(['comments' => $comments]);
    }
} 