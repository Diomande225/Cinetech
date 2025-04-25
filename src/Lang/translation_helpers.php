<?php

/**
 * Helper functions for translations with caching
 */

use App\Services\TranslationCache;
use App\Lang\Language;

/**
 * Traduit un texte avec mise en cache sur disque
 * Cette fonction est plus performante que translateExternal car elle garde
 * les traductions en cache sur le disque au lieu de les récupérer de l'API à chaque fois
 *
 * @param string $text Texte à traduire
 * @param string $sourceLang Langue source (défaut: en)
 * @param string|null $targetLang Langue cible (défaut: langue courante de l'utilisateur)
 * @return string Texte traduit
 */
function translateWithCache(string $text, string $sourceLang = 'en', ?string $targetLang = null): string
{
    // Si le texte est vide, ne pas traduire
    if (empty(trim($text))) {
        return $text;
    }
    
    // Utiliser la langue courante si non spécifiée
    if (is_null($targetLang)) {
        $targetLang = getCurrentLanguage();
    }
    
    // Si la langue source est la même que la langue cible, pas besoin de traduire
    if ($sourceLang === $targetLang) {
        return $text;
    }
    
    // Initialiser le cache
    $cache = new TranslationCache();
    
    // Essayer de récupérer du cache
    $cachedTranslation = $cache->get($text, $sourceLang, $targetLang);
    
    if ($cachedTranslation !== null) {
        return $cachedTranslation;
    }
    
    // Si pas en cache, traduire avec la classe Language existante
    $languageInstance = Language::getInstance();
    $translation = $languageInstance->translateExternal($text, $sourceLang);
    
    // Mettre en cache pour utilisation future (persistance sur disque)
    $cache->set($text, $translation, $sourceLang, $targetLang);
    
    return $translation;
}

/**
 * Nettoie les traductions expirées du cache
 * Utile à exécuter périodiquement via une tâche cron ou une action d'administration
 * 
 * @return int Nombre d'entrées supprimées
 */
function cleanTranslationCache(): int
{
    $cache = new TranslationCache();
    return $cache->cleanExpired();
} 