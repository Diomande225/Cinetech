<?php
function isInFavorites($contentId, $contentType) {
    if (!isset($_SESSION['user'])) return false;
    
    global $db;
    $stmt = $db->prepare("SELECT id FROM favorites WHERE user_id = ? AND content_id = ? AND content_type = ?");
    $stmt->execute([$_SESSION['user']['id'], $contentId, $contentType]);
    return $stmt->fetch() !== false;
} 