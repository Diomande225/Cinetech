<?php
class ImageService {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct() {
        $this->uploadDir = __DIR__ . '/../public/uploads/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function uploadImage($file, $type = 'avatar') {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Paramètres invalides.');
        }

        $this->validateUpload($file);

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid($type . '_') . '.' . $extension;
        $filepath = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new RuntimeException('Échec du téléchargement.');
        }

        // Redimensionner l'image si nécessaire
        if ($type === 'avatar') {
            $this->resizeImage($filepath, 200, 200);
        }

        return $filename;
    }

    private function validateUpload($file) {
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Fichier trop volumineux.');
            default:
                throw new RuntimeException('Erreur inconnue.');
        }

        if ($file['size'] > $this->maxSize) {
            throw new RuntimeException('Fichier trop volumineux.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (!in_array($finfo->file($file['tmp_name']), $this->allowedTypes)) {
            throw new RuntimeException('Format de fichier non autorisé.');
        }
    }

    private function resizeImage($filepath, $maxWidth, $maxHeight) {
        list($width, $height) = getimagesize($filepath);
        
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return;
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        $source = imagecreatefromstring(file_get_contents($filepath));
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled(
            $destination, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );

        switch (exif_imagetype($filepath)) {
            case IMAGETYPE_JPEG:
                imagejpeg($destination, $filepath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($destination, $filepath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($destination, $filepath);
                break;
        }

        imagedestroy($source);
        imagedestroy($destination);
    }
} 