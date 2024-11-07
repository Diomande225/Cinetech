<?php

if (!function_exists('truncateText')) {
    function truncateText($text, $length = 150) {
        if (empty($text)) return '';
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}

if (!function_exists('getImageUrl')) {
    function getImageUrl($path, $size = 'w500') {
        if (empty($path)) {
            return '/assets/images/no-image.jpg';
        }
        return "https://image.tmdb.org/t/p/{$size}{$path}";
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date) {
        if (empty($date)) return 'Date inconnue';
        return date('d/m/Y', strtotime($date));
    }
}

if (!function_exists('isAuthenticated')) {
    function isAuthenticated() {
        return isset($_SESSION['user']);
    }
}

if (!function_exists('getCurrentUser')) {
    function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
}

function isInFavorites($contentId, $contentType) {
    if (!isset($_SESSION['user'])) return false;
    
    global $db;
    $stmt = $db->prepare("SELECT id FROM favorites WHERE user_id = ? AND content_id = ? AND content_type = ?");
    $stmt->execute([$_SESSION['user']['id'], $contentId, $contentType]);
    return $stmt->fetch() !== false;
} 