<?php
namespace App\Models;

class FavorisModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addFavori($userId, $itemId, $mediaType) {
        error_log("Tentative d'ajout de favori - UserID: $userId, ItemID: $itemId, MediaType: $mediaType");
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, item_id, media_type) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $itemId, $mediaType]);
        error_log("Favori ajouté avec succès");
    }

    public function removeFavori($userId, $itemId, $mediaType) {
        error_log("Tentative de suppression de favori - UserID: $userId, ItemID: $itemId, MediaType: $mediaType");
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND item_id = ? AND media_type = ?");
        $stmt->execute([$userId, $itemId, $mediaType]);
        error_log("Favori supprimé avec succès");
    }

    public function getFavoris($userId) {
        error_log("Récupération des favoris - UserID: $userId");
        $stmt = $this->db->prepare("SELECT * FROM favorites WHERE user_id = ?");
        $stmt->execute([$userId]);
        $favoris = $stmt->fetchAll();
        error_log("Nombre de favoris trouvés: " . count($favoris));
        return $favoris;
    }

    public function getUserFavoriteIds($userId) {
        error_log("Récupération des IDs des favoris - UserID: $userId");
        $stmt = $this->db->prepare("SELECT item_id FROM favorites WHERE user_id = ?");
        $stmt->execute([$userId]);
        $ids = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        error_log("Nombre d'IDs trouvés: " . count($ids));
        return $ids;
    }

    public function exists($userId, $itemId, $mediaType) {
        error_log("Vérification de l'existence du favori - UserID: $userId, ItemID: $itemId, MediaType: $mediaType");
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND item_id = ? AND media_type = ?");
        $stmt->execute([$userId, $itemId, $mediaType]);
        $exists = $stmt->fetchColumn() > 0;
        error_log("Le favori " . ($exists ? "existe" : "n'existe pas"));
        return $exists;
    }
}