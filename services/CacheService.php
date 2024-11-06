<?php
class CacheService {
    private $cacheDir;
    private $defaultDuration;

    public function __construct() {
        $this->cacheDir = __DIR__ . '/../cache/';
        $this->defaultDuration = 3600; // 1 heure
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function get($key) {
        $filename = $this->getCacheFilename($key);
        
        if (!file_exists($filename)) {
            return null;
        }

        $content = file_get_contents($filename);
        $data = unserialize($content);

        if ($data['expiry'] < time()) {
            unlink($filename);
            return null;
        }

        return $data['content'];
    }

    public function set($key, $content, $duration = null) {
        $duration = $duration ?? $this->defaultDuration;
        $data = [
            'content' => $content,
            'expiry' => time() + $duration
        ];

        $filename = $this->getCacheFilename($key);
        file_put_contents($filename, serialize($data));
    }

    public function delete($key) {
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function clear() {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function getCacheFilename($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }
} 