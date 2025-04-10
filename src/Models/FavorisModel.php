<?php
namespace App\Models;

class FavorisModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addFavori($userId, $itemId, $mediaType) {
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, item_id, media_type) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $itemId, $mediaType]);
    }

    public function removeFavori($userId, $itemId) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND item_id = ?");
        $stmt->execute([$userId, $itemId]);
    }

    public function getFavoris($userId) {
        $stmt = $this->db->prepare("SELECT * FROM favorites WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getUserFavoriteIds($userId) {
        $stmt = $this->db->prepare("SELECT item_id FROM favorites WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function exists($userId, $itemId, $mediaType) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND item_id = ? AND media_type = ?");
        $stmt->execute([$userId, $itemId, $mediaType]);
        return $stmt->fetchColumn() > 0;
    }
}