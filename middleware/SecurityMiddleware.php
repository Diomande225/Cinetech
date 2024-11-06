<?php
class SecurityMiddleware {
    public function handle() {
        // Protection CSRF
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                http_response_code(403);
                die('Action non autorisée');
            }
        }

        // En-têtes de sécurité
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self' https://api.themoviedb.org; img-src 'self' https://image.tmdb.org data:; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com");

        // Protection contre les injections SQL (si PDO n'est pas utilisé)
        if (isset($_GET)) {
            $_GET = $this->sanitizeData($_GET);
        }
        if (isset($_POST)) {
            $_POST = $this->sanitizeData($_POST);
        }
    }

    private function sanitizeData($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeData($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
} 