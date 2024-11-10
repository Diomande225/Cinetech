<?php 
$pageTitle = $show['name'] . ' - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="show-details">
    <div class="backdrop" style="background-image: url(<?= getImageUrl($show['backdrop_path'], 'original') ?>)">
        <div class="overlay">
            <div class="show-header">
                <h1><?= htmlspecialchars($show['name']) ?></h1>
                <?php if (isAuthenticated()): ?>
                    <button class="favorite-btn <?= $isFavorite ? 'active' : '' ?>" 
                            data-id="<?= $show['id'] ?>" 
                            data-type="tv">
                        <i class="fas fa-heart"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="show-content">
        <div class="show-info">
            <img src="<?= getImageUrl($show['poster_path']) ?>" 
                 alt="<?= htmlspecialchars($show['name']) ?>" 
                 class="poster">
            
            <div class="details">
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span><?= number_format($show['vote_average'], 1) ?></span>
                </div>
                
                <p class="first-air-date">
                    Première diffusion : <?= formatDate($show['first_air_date']) ?>
                </p>
                
                <div class="genres">
                    <?php foreach ($show['genres'] as $genre): ?>
                        <span class="genre"><?= $genre['name'] ?></span>
                    <?php endforeach; ?>
                </div>
                
                <p class="overview"><?= $show['overview'] ?></p>

                <div class="seasons">
                    <h3>Saisons</h3>
                    <p><?= $show['number_of_seasons'] ?> saison(s), <?= $show['number_of_episodes'] ?> épisode(s)</p>
                </div>
            </div>
        </div>

        <!-- Section Commentaires -->
        <div class="comments-section">
            <h2>Commentaires</h2>
            
            <?php if (isAuthenticated()): ?>
                <form class="comment-form" method="POST" action="/tv-show/<?= $show['id'] ?>/comment">
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

<script>
function playTrailer(key) {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = `https://www.youtube.com/embed/${key}?autoplay=1`;
    modal.classList.remove('hidden');
}

function closeTrailer() {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = '';
    modal.classList.add('hidden');
}
</script>

<?php require_once 'includes/footer.php'; ?> 