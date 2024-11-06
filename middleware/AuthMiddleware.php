<?php
class AuthMiddleware {
    public function handle() {
        if (!isset($_SESSION['user'])) {
            if ($this->isProtectedRoute()) {
                header('Location: /login');
                exit;
            }
        } else {
            if ($this->isGuestOnlyRoute()) {
                header('Location: /');
                exit;
            }
        }
    }

    private function isProtectedRoute() {
        $protectedRoutes = [
            '/profile',
            '/favorites',
            '/settings'
        ];

        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return in_array($currentPath, $protectedRoutes);
    }

    private function isGuestOnlyRoute() {
        $guestRoutes = [
            '/login',
            '/register'
        ];

        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return in_array($currentPath, $guestRoutes);
    }
} 