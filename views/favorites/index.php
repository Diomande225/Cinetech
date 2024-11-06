<?php 
$pageTitle = 'Mes Favoris - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="favorites-container">
    <h1>Mes Favoris</h1>

    <?php if (empty($favorites)): ?>
        <div class="empty-favorites">
            <p>Vous n'avez pas encore de favoris.</p>
            <div class="suggestions">
                <a href="/movies" class="btn">Découvrir des films</a>
                <a href="/tv-shows" class="btn">Découvrir des séries</a>
            </div>
        </div>
    <?php else: ?>
        <div class="favorites-grid">
            <?php foreach ($favorites as $favorite): ?>
                <div class="favorite-card">
                    <img src="<?= getImageUrl($favorite['poster_path']) ?>" 
                         alt="<?= htmlspecialchars($favorite['title'] ?? $favorite['name']) ?>">
                    <div class="favorite-info">
                        <h3><?= htmlspecialchars($favorite['title'] ?? $favorite['name']) ?></h3>
                        <p class="type"><?= $favorite['content_type'] === 'movie' ? 'Film' : 'Série' ?></p>
                        <div class="actions">
                            <a href="/<?= $favorite['content_type'] ?>/<?= $favorite['content_id'] ?>" 
                               class="btn-view">Voir</a>
                            <button class="btn-remove" 
                                    data-id="<?= $favorite['content_id'] ?>" 
                                    data-type="<?= $favorite['content_type'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?> 