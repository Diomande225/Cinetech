<?php 
$pageTitle = 'Films - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="movies-container">
    <h1>Films Populaires</h1>
    
    <div class="content-grid">
        <?php foreach ($movies['results'] as $movie): ?>
            <div class="content-card">
                <img src="<?= getImageUrl($movie['poster_path']) ?>" 
                     alt="<?= htmlspecialchars($movie['title']) ?>">
                <div class="content-info">
                    <h3><?= htmlspecialchars($movie['title']) ?></h3>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <span><?= number_format($movie['vote_average'], 1) ?></span>
                    </div>
                    <p><?= truncateText($movie['overview']) ?></p>
                    <a href="/movie/<?= $movie['id'] ?>" class="btn-details">Voir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($movies['page'] > 1): ?>
            <a href="?page=<?= $movies['page'] - 1 ?>" class="btn">Précédent</a>
        <?php endif; ?>
        
        <?php if ($movies['page'] < $movies['total_pages']): ?>
            <a href="?page=<?= $movies['page'] + 1 ?>" class="btn">Suivant</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 