<?php 
$pageTitle = $movie['title'] . ' - La Cinétech';
require_once 'includes/header.php'; 
?>

<!-- Modal Trailer -->
<div id="trailerModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-75"></div>
    <div class="relative z-10 w-full h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-black rounded-lg">
            <button class="absolute -top-10 right-0 text-white text-xl" onclick="closeTrailer()">
                <i class="fas fa-times"></i>
            </button>
            <div class="aspect-w-16 aspect-h-9">
                <iframe id="trailerFrame" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<div class="movie-details">
    <div class="backdrop" style="background-image: url(<?= getImageUrl($movie['backdrop_path'], 'original') ?>)">
        <div class="overlay">
            <div class="movie-header">
                <h1><?= htmlspecialchars($movie['title']) ?></h1>
                
                <!-- Boutons d'action -->
                <div class="action-buttons">
                    <?php if (!empty($videos)): ?>
                        <button onclick="playTrailer('<?= $videos[0]['key'] ?>')" class="btn-primary">
                            <i class="fas fa-play"></i> Bande annonce
                        </button>
                    <?php endif; ?>

                    <?php if (isAuthenticated()): ?>
                        <button class="favorite-btn <?= $isFavorite ? 'active' : '' ?>" 
                                onclick="toggleFavorite(<?= $movie['id'] ?>)">
                            <i class="fas fa-heart"></i>
                        </button>
                    <?php endif; ?>

                    <!-- Boutons de partage -->
                    <div class="share-buttons">
                        <button onclick="share('facebook')" class="btn-share facebook">
                            <i class="fab fa-facebook"></i>
                        </button>
                        <button onclick="share('twitter')" class="btn-share twitter">
                            <i class="fab fa-twitter"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Note utilisateur -->
            <?php if (isAuthenticated()): ?>
                <div class="user-rating">
                    <h3>Votre note</h3>
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star star-rating" 
                               data-rating="<?= $i ?>" 
                               onclick="rateMovie(<?= $movie['id'] ?>, <?= $i ?>)"></i>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>
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

<!-- Ajoutez ce JavaScript à la fin du fichier -->
<script>
async function toggleFavorite(movieId) {
    try {
        const response = await fetch('/movie/favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ movie_id: movieId })
        });
        
        const data = await response.json();
        const button = document.querySelector('.favorite-btn');
        
        if (data.status === 'added') {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

async function rateMovie(movieId, rating) {
    try {
        const response = await fetch('/movie/rate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ movie_id: movieId, rating: rating })
        });
        
        const data = await response.json();
        if (data.success) {
            // Mettre à jour l'affichage des étoiles
            updateStarDisplay(rating);
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

function share(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    
    let shareUrl;
    if (platform === 'facebook') {
        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
    } else if (platform === 'twitter') {
        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
    }
    
    window.open(shareUrl, '_blank', 'width=600,height=400');
}

// Fonctions pour la bande-annonce
function playTrailer(videoId) {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    modal.classList.remove('hidden');
}

function closeTrailer() {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = '';
    modal.classList.add('hidden');
}

// Mise à jour de l'affichage des étoiles
function updateStarDisplay(rating) {
    const stars = document.querySelectorAll('.star-rating');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}
</script>

<?php require_once 'includes/footer.php'; ?> 