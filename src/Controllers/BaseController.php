<?php

namespace App\Controllers;

use App\Views\View;
use App\Lang\Language;

class BaseController {
    protected $view;
    protected $language;
    
    public function __construct() {
        $this->view = new View();
        
        // Initialiser le gestionnaire de langue
        $this->language = Language::getInstance();
    }
    
    /**
     * Récupère une traduction
     */
    protected function translate($key, $replacements = []) {
        return $this->language->get($key, $replacements);
    }
    
    /**
     * Récupère la langue actuelle
     */
    protected function getCurrentLanguage() {
        return $this->language->getCurrentLanguage();
    }
} 