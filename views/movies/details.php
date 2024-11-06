<?php 
$pageTitle = $movie['title'] . ' - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="movie-details">
    <div class="backdrop" style="background-image: url(<?= getImageUrl($movie['backdrop_path'], 'original') ?>)">
        <div class="overlay">
            <div class="movie-header">
                <h1><?= htmlspecialchars($movie['title']) ?></h1>
                <?php if (isAuthenticated()): ?>
                    <button class="favorite-btn <?= $isFavorite ? 'active' : '' ?>" 
                            data-id="<?= $movie['id'] ?>" 
                            data-type="movie">
                        <i class="fas fa-heart"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="movie-content">
        <div class="movie-info">
            <img src="<?= getImageUrl($movie['poster_path']) ?>" 
                 alt="<?= htmlspecialchars($movie['title']) ?>" 
                 class="poster">
            
            <div class="details">
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span><?= number_format($movie['vote_average'], 1) ?></span>
                </div>
                
                <p class="release-date">
                    Date de sortie : <?= formatDate($movie['release_date']) ?>
                </p>
                
                <div class="genres">
                    <?php foreach ($movie['genres'] as $genre): ?>
                        <span class="genre"><?= $genre['name'] ?></span>
                    <?php endforeach; ?>
                </div>
                
                <p class="overview"><?= $movie['overview'] ?></p>
            </div>
        </div>

        <!-- Section Commentaires -->
        <div class="comments-section">
            <h2>Commentaires</h2>
            
            <?php if (isAuthenticated()): ?>
                <form class="comment-form" method="POST" action="/movie/<?= $movie['id'] ?>/comment">
                    <textarea name="comment" placeholder="Ajouter un commentaire..." required></textarea>
                    <button type="submit">Publier</button>
                </form>
            <?php endif; ?>

            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <strong><?= htmlspecialchars($comment['username']) ?></strong>
                            <span class="date"><?= formatDate($comment['created_at']) ?></span>
                        </div>
                        <p><?= htmlspecialchars($comment['comment_text']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 