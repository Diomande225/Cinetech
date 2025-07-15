<?php
namespace App\Controllers;

use App\Views\View;
use App\Models\User;

class ProfileController {
    
    public function show() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Cinetech/Login');
            exit;
        }

        try {
            $user = User::findById($_SESSION['user_id']);
            
            $view = new View();
            $view->render('profile', [
                'title' => 'Profil',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            error_log("Profile error: " . $e->getMessage());
            header('Location: /Cinetech/Login');
            exit;
        }
    }
}