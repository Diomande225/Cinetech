<?php
/**
 * Script de nettoyage du cache de traduction
 * 
 * Ce script peut être exécuté manuellement ou ajouté à une tâche CRON
 * pour nettoyer régulièrement les traductions expirées du cache.
 */

// Chargement de l'autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des fonctions d'aide
require_once __DIR__ . '/../src/Lang/helpers.php';

// Nettoyage du cache
$deletedCount = cleanTranslationCache();

echo "Nettoyage du cache de traduction terminé." . PHP_EOL;
echo "Nombre d'entrées supprimées : $deletedCount" . PHP_EOL; 