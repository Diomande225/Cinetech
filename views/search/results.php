<?php 
$pageTitle = 'Résultats de recherche - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="search-results">
    <h1>Résultats pour "<?= htmlspecialchars($query) ?>"</h1>

    <?php if (empty($results['results'])): ?>
        <p class="no-results">Aucun résultat trouvé pour votre recherche.</p>
    <?php else: ?>
        <div class="content-grid">
            <?php foreach ($results['results'] as $result): ?>
                <?php if ($result['media_type'] === 'movie' || $result['media_type'] === 'tv'): ?>
                    <div class="content-card">
                        <img src="<?= getImageUrl($result['poster_path']) ?>" 
                             alt="<?= htmlspecialchars($result['title'] ?? $result['name']) ?>">
                        <div class="content-info">
                            <h3><?= htmlspecialchars($result['title'] ?? $result['name']) ?></h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span><?= number_format($result['vote_average'], 1) ?></span>
                            </div>
                            <p><?= truncateText($result['overview']) ?></p>
                            <a href="/<?= $result['media_type'] ?>/<?= $result['id'] ?>" 
                               class="btn-details">Voir plus</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php if ($results['page'] > 1): ?>
                <a href="?q=<?= urlencode($query) ?>&page=<?= $results['page'] - 1 ?>" 
                   class="btn">Précédent</a>
            <?php endif; ?>
            
            <?php if ($results['page'] < $results['total_pages']): ?>
                <a href="?q=<?= urlencode($query) ?>&page=<?= $results['page'] + 1 ?>" 
                   class="btn">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?> 