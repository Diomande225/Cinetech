<?php 
$pageTitle = $movieDetails['title'] . ' - La Cinétech';
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

<!-- Hero Section -->
<div class="relative min-h-screen">
    <!-- Backdrop -->
    <div class="absolute inset-0">
        <img src="https://image.tmdb.org/t/p/original<?= $movieDetails['backdrop_path'] ?>" 
             alt="" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
    </div>

    <!-- Content -->
    <div class="relative container mx-auto px-4 pt-32 pb-16">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Poster -->
            <div class="w-64 flex-shrink-0">
                <div class="relative group">
                    <img src="https://image.tmdb.org/t/p/w500<?= $movieDetails['poster_path'] ?>" 
                         alt="<?= htmlspecialchars($movieDetails['title']) ?>"
                         class="w-full rounded-lg shadow-lg">
                    
                    <!-- Bouton Favoris -->
                    <?php if (isset($_SESSION['user'])): ?>
                    <button onclick="toggleFavorite(event, <?= $movieDetails['id'] ?>, 'movie')" 
                            class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75
                                   <?= $isFavorite ? 'active' : '' ?>">
                        <i class="fas fa-heart text-xl"></i>
                    </button>
                    <?php endif; ?>
                </div>

                <?php if ($trailer): ?>
                <button onclick="playTrailer('<?= $trailer['key'] ?>')"
                        class="w-full mt-4 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-play"></i>
                    Bande annonce
                </button>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2"><?= htmlspecialchars($movieDetails['title']) ?></h1>
                
                <?php if (!empty($movieDetails['tagline'])): ?>
                <p class="text-xl text-gray-400 mb-4"><?= htmlspecialchars($movieDetails['tagline']) ?></p>
                <?php endif; ?>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        <span><?= number_format($movieDetails['vote_average'], 1) ?></span>
                    </div>
                    <span><?= date('Y', strtotime($movieDetails['release_date'])) ?></span>
                    <span><?= $movieDetails['runtime'] ?> min</span>
                </div>

                <!-- Genres -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php foreach ($movieDetails['genres'] as $genre): ?>
                        <span class="px-3 py-1 bg-gray-800 rounded-full text-sm">
                            <?= htmlspecialchars($genre['name']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <!-- Synopsis -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Synopsis</h2>
                    <p class="text-gray-300"><?= nl2br(htmlspecialchars($movieDetails['overview'])) ?></p>
                </div>

                <!-- Casting -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Casting</h2>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <?php foreach ($cast as $actor): ?>
                            <div class="text-center">
                                <?php if ($actor['profile_path']): ?>
                                    <img src="https://image.tmdb.org/t/p/w185<?= $actor['profile_path'] ?>" 
                                         alt="<?= htmlspecialchars($actor['name']) ?>"
                                         class="w-full rounded-lg mb-2">
                                <?php endif; ?>
                                <p class="font-semibold"><?= htmlspecialchars($actor['name']) ?></p>
                                <p class="text-sm text-gray-400"><?= htmlspecialchars($actor['character']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Commentaires -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Commentaires</h2>
                    
                    <?php if (isset($_SESSION['user'])): ?>
                        <form action="/Cinetech/movie/comment" method="POST" class="mb-6">
                            <input type="hidden" name="movie_id" value="<?= $movieDetails['id'] ?>">
                            <textarea name="comment" 
                                      class="w-full p-4 bg-gray-800 rounded-lg resize-none mb-2"
                                      rows="3"
                                      placeholder="Ajouter un commentaire..."></textarea>
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Publier
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="mb-6">
                            <a href="/Cinetech/login" class="text-red-600 hover:underline">Connectez-vous</a> 
                            pour laisser un commentaire
                        </p>
                    <?php endif; ?>

                    <div class="space-y-4">
                        <?php foreach ($comments as $comment): ?>
                            <div class="bg-gray-800 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-semibold"><?= htmlspecialchars($comment['username']) ?></div>
                                    <div class="text-sm text-gray-400">
                                        <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                    </div>
                                </div>
                                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

// Fermer avec Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeTrailer();
});
</script>

<?php require_once 'includes/footer.php'; ?> 