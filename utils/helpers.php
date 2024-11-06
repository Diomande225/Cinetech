<?php
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function getImageUrl($path, $size = 'w500') {
    if (!$path) {
        return '/assets/images/no-image.jpg';
    }
    return "https://image.tmdb.org/t/p/{$size}{$path}";
}

function isAuthenticated() {
    return isset($_SESSION['user']);
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function redirect($path) {
    header("Location: /$path");
    exit;
}

function flashMessage($key = null, $value = null) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    if ($key === null) {
        $messages = $_SESSION['flash'];
        $_SESSION['flash'] = [];
        return $messages;
    }

    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
    }
} 