<?php 
$pageTitle = 'Séries TV - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="tv-shows-container">
    <h1>Séries Populaires</h1>
    
    <div class="content-grid">
        <?php foreach ($shows['results'] as $show): ?>
            <div class="content-card">
                <img src="<?= getImageUrl($show['poster_path']) ?>" 
                     alt="<?= htmlspecialchars($show['name']) ?>">
                <div class="content-info">
                    <h3><?= htmlspecialchars($show['name']) ?></h3>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <span><?= number_format($show['vote_average'], 1) ?></span>
                    </div>
                    <p><?= truncateText($show['overview']) ?></p>
                    <a href="/tv-show/<?= $show['id'] ?>" class="btn-details">Voir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($shows['page'] > 1): ?>
            <a href="?page=<?= $shows['page'] - 1 ?>" class="btn">Précédent</a>
        <?php endif; ?>
        
        <?php if ($shows['page'] < $shows['total_pages']): ?>
            <a href="?page=<?= $shows['page'] + 1 ?>" class="btn">Suivant</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 