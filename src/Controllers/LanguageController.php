<?php

namespace App\Controllers;

use App\Lang\Language;

class LanguageController extends BaseController {
    
    /**
     * Change la langue de l'application
     * 
     * @param string $lang Le code de la langue (fr, en, etc.)
     */
    public function changeLanguage($lang) {
        // Récupérer l'instance de Language
        $language = Language::getInstance();
        
        // Définir la nouvelle langue
        $language->setLanguage($lang);
        
        // Rediriger vers la page précédente
        $referer = $_SERVER['HTTP_REFERER'] ?? '/Cinetech/home';
        header('Location: ' . $referer);
        exit();
    }
} 