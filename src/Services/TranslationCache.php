<?php

namespace App\Services;

/**
 * Classe pour gérer le cache des traductions
 */
class TranslationCache
{
    private string $cacheDir;
    private int $defaultExpiration = 604800; // 7 jours par défaut

    public function __construct()
    {
        // Définir le répertoire de cache
        $this->cacheDir = dirname(__DIR__, 2) . '/cache/translations';
        
        // Créer le répertoire de cache s'il n'existe pas
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Récupère une traduction du cache
     *
     * @param string $sourceText Le texte source à traduire
     * @param string $sourceLang La langue source
     * @param string $targetLang La langue cible
     * @return string|null La traduction si trouvée en cache, null sinon
     */
    public function get(string $sourceText, string $sourceLang = 'en', string $targetLang = 'fr'): ?string
    {
        $cacheKey = $this->generateCacheKey($sourceText, $sourceLang, $targetLang);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';

        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);

            // Vérifier si le cache est expiré
            if (time() < $cacheData['expiration']) {
                return $cacheData['translation'];
            } else {
                // Supprimer le cache expiré
                @unlink($cacheFile);
            }
        }

        return null;
    }

    /**
     * Stocke une traduction dans le cache
     *
     * @param string $sourceText Le texte source
     * @param string $translation La traduction
     * @param string $sourceLang La langue source
     * @param string $targetLang La langue cible
     * @param int|null $expiration Durée de vie en secondes (utilise la valeur par défaut si null)
     * @return bool Succès ou échec
     */
    public function set(string $sourceText, string $translation, string $sourceLang = 'en', string $targetLang = 'fr', ?int $expiration = null): bool
    {
        $cacheKey = $this->generateCacheKey($sourceText, $sourceLang, $targetLang);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';

        $expiration = $expiration ?? $this->defaultExpiration;
        $expirationTime = time() + $expiration;

        $cacheData = [
            'source' => $sourceText,
            'translation' => $translation,
            'source_lang' => $sourceLang,
            'target_lang' => $targetLang,
            'created' => time(),
            'expiration' => $expirationTime
        ];

        return file_put_contents($cacheFile, json_encode($cacheData)) !== false;
    }

    /**
     * Génère une clé de cache unique pour la combinaison texte/langues
     *
     * @param string $sourceText Le texte source
     * @param string $sourceLang La langue source
     * @param string $targetLang La langue cible
     * @return string La clé de cache
     */
    private function generateCacheKey(string $sourceText, string $sourceLang, string $targetLang): string
    {
        return md5($sourceText . '_' . $sourceLang . '_' . $targetLang);
    }

    /**
     * Nettoie les entrées de cache expirées
     *
     * @return int Nombre d'entrées supprimées
     */
    public function cleanExpired(): int
    {
        $count = 0;
        $files = glob($this->cacheDir . '/*.json');

        foreach ($files as $file) {
            if (file_exists($file)) {
                $cacheData = json_decode(file_get_contents($file), true);

                if (time() > $cacheData['expiration']) {
                    if (@unlink($file)) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }
} 