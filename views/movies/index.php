<?php 
$pageTitle = 'Films - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="container mx-auto px-4 pt-32">
    <!-- Films Populaires -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Films Populaires</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($movies['popular']['results'] as $movie): ?>
                <div class="relative overflow-hidden rounded-lg group">
                    <?php if ($movie['poster_path']): ?>
                        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                             alt="<?= htmlspecialchars($movie['title']) ?>"
                             class="w-full h-auto transition-transform duration-300 group-hover:scale-105">
                        
                        <!-- Bouton Favoris -->
                        <?php if (isset($_SESSION['user'])): ?>
                            <button 
                                class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-10"
                                data-media-id="<?= $movie['id'] ?>"
                                data-media-type="movie">
                                <i class="fas fa-heart <?= isFavorite($movie['id'], 'movie') ? 'text-red-600' : 'text-white' ?>"></i>
                            </button>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 p-4">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($movie['title']) ?></h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    <span><?= number_format($movie['vote_average'], 1) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?> 