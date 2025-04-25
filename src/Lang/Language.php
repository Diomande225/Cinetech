<?php

namespace App\Lang;

use App\Services\TranslationCache;

class Language {
    private static $instance = null;
    private $lang = 'fr'; // Langue par défaut
    private $translations = [];
    private $translationCache = null;
    
    private function __construct() {
        $this->setLanguage($this->getPreferredLanguage());
        $this->translationCache = new TranslationCache();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Définit la langue actuelle et charge les traductions
     */
    public function setLanguage($lang) {
        // Vérifier si la langue est supportée
        if ($this->isLanguageSupported($lang)) {
            $this->lang = $lang;
        } else {
            $this->lang = 'fr'; // Langue par défaut si non supportée
        }
        
        // Charger les traductions
        $this->loadTranslations();
        
        // Sauvegarder la préférence dans la session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['language'] = $this->lang;
    }
    
    /**
     * Vérifie si une langue est supportée
     */
    public function isLanguageSupported($lang) {
        return in_array($lang, ['fr', 'en', 'it', 'es', 'ru']);
    }
    
    /**
     * Récupère la langue préférée de l'utilisateur (session ou navigateur)
     */
    public function getPreferredLanguage() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérifier si une langue est déjà définie dans la session
        if (isset($_SESSION['language'])) {
            return $_SESSION['language'];
        }
        
        // Vérifier la langue du navigateur
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if ($this->isLanguageSupported($browserLang)) {
                return $browserLang;
            }
        }
        
        // Par défaut, retourner le français
        return 'fr';
    }
    
    /**
     * Charge les traductions pour la langue actuelle
     */
    private function loadTranslations() {
        $file = __DIR__ . '/' . $this->lang . '/main.php';
        
        if (file_exists($file)) {
            $this->translations = require $file;
        } else {
            // Si le fichier n'existe pas, utiliser le français par défaut
            $file = __DIR__ . '/fr/main.php';
            if (file_exists($file)) {
                $this->translations = require $file;
            } else {
                $this->translations = [];
            }
        }
    }
    
    /**
     * Récupère la traduction d'une clé
     */
    public function get($key, $replacements = []) {
        if (isset($this->translations[$key])) {
            $translation = $this->translations[$key];
            
            // Remplacer les variables dans la chaîne
            foreach ($replacements as $search => $replace) {
                $translation = str_replace('{' . $search . '}', $replace, $translation);
            }
            
            return $translation;
        }
        
        // Si la clé n'existe pas, la retourner telle quelle
        return $key;
    }
    
    /**
     * Récupère la langue actuelle
     */
    public function getCurrentLanguage() {
        return $this->lang;
    }
    
    /**
     * Retourne toutes les langues disponibles
     */
    public function getAvailableLanguages() {
        return [
            'fr' => 'Français',
            'en' => 'English',
            'it' => 'Italiano',
            'es' => 'Español',
            'ru' => 'Русский'
        ];
    }
    
    /**
     * Traduit automatiquement un texte externe via API (utilise Google Translate API)
     * Cette version utilise un cache sur disque pour de meilleures performances
     */
    public function translateExternal($text, $sourceLanguage = 'en') {
        // Si le texte est vide ou la langue source est la même que la langue cible, retourner le texte tel quel
        if (empty($text) || $sourceLanguage === $this->lang) {
            return $text;
        }
        
        // Initialiser le cache si nécessaire
        if ($this->translationCache === null) {
            $this->translationCache = new TranslationCache();
        }
        
        // Vérifier si la traduction est déjà en cache sur disque
        $cachedTranslation = $this->translationCache->get($text, $sourceLanguage, $this->lang);
        
        if ($cachedTranslation !== null) {
            return $cachedTranslation;
        }
        
        // Si pas en cache sur disque, vérifier le cache en session temporaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Générer une clé unique pour ce texte et cette langue (cache secondaire en session)
        $cacheKey = 'translated_' . md5($text) . '_' . $this->lang;
        
        // Vérifier si la traduction est déjà en cache en session
        if (isset($_SESSION[$cacheKey])) {
            // Sauvegarder en cache sur disque pour la prochaine fois
            $translation = $_SESSION[$cacheKey];
            $this->translationCache->set($text, $translation, $sourceLanguage, $this->lang);
            return $translation;
        }
        
        // Utiliser l'API Google Translate
        $translatedText = $this->translateWithGoogleAPI($text, $sourceLanguage, $this->lang);
        
        // Stocker en cache sur disque et en session
        $this->translationCache->set($text, $translatedText, $sourceLanguage, $this->lang);
        $_SESSION[$cacheKey] = $translatedText;
        
        return $translatedText;
    }
    
    /**
     * Traduit un texte avec l'API Google Translate gratuite
     * Cette implémentation utilise l'API publique de Google Translate (non officielle)
     */
    private function translateWithGoogleAPI($text, $from, $to) {
        // Si c'est la même langue, renvoyer le texte original
        if ($from === $to) {
            return $text;
        }
        
        try {
            // Préparer l'URL de l'API Google Translate
            $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl='
                . urlencode($from) . '&tl=' . urlencode($to) 
                . '&dt=t&q=' . urlencode($text);
            
            // Définir un user agent pour éviter d'être bloqué
            $options = [
                'http' => [
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n",
                    'method' => 'GET'
                ]
            ];
            
            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            
            if ($response === false) {
                // Échec de la requête, renvoyer le texte original
                error_log("Erreur de traduction: Échec de la requête à l'API Google Translate");
                return $text;
            }
            
            // Décoder la réponse JSON
            $responseArray = json_decode($response, true);
            
            if (!$responseArray || !isset($responseArray[0])) {
                // Réponse invalide, renvoyer le texte original
                error_log("Erreur de traduction: Réponse invalide de l'API Google Translate");
                return $text;
            }
            
            // Construire la traduction complète à partir des segments
            $translatedText = '';
            foreach ($responseArray[0] as $segment) {
                if (isset($segment[0])) {
                    $translatedText .= $segment[0];
                }
            }
            
            return $translatedText ?: $text;
            
        } catch (\Exception $e) {
            // En cas d'erreur, log et renvoyer le texte original
            error_log("Erreur lors de la traduction: " . $e->getMessage());
            return $text;
        }
    }
} 