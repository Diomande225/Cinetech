<?php

use App\Lang\Language;

// Inclure les aides à la traduction avec cache
require_once __DIR__ . '/translation_helpers.php';

/**
 * Récupère la traduction d'une clé
 *
 * @param string $key La clé de traduction
 * @param array $replacements Variables à remplacer dans la traduction
 * @return string
 */
function __($key, $replacements = []) {
    return Language::getInstance()->get($key, $replacements);
}

/**
 * Récupère la langue actuelle
 *
 * @return string Le code de la langue (fr, en, etc.)
 */
function getCurrentLanguage() {
    return Language::getInstance()->getCurrentLanguage();
}

/**
 * Récupère les langues disponibles
 *
 * @return array Les langues disponibles
 */
function getAvailableLanguages() {
    return Language::getInstance()->getAvailableLanguages();
}

/**
 * Traduit un texte externe provenant d'une API
 * 
 * @deprecated Utilisez translateWithCache() à la place pour de meilleures performances
 * @param string $text Le texte à traduire
 * @param string $sourceLanguage La langue source du texte (par défaut 'en')
 * @return string Le texte traduit
 */
function translateExternal($text, $sourceLanguage = 'en') {
    // Utiliser la nouvelle fonction avec cache pour de meilleures performances
    return translateWithCache($text, $sourceLanguage);
} 