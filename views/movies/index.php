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
                        
                        <!-- Ajoutez ceci temporairement pour déboguer -->
                        <?php if (isset($_SESSION['user'])): ?>
                            <pre><?php var_dump($_SESSION['user']); ?></pre>
                        <?php endif; ?>
                        
                        <!-- Le bouton -->
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

        <!-- Pagination -->
        <div class="flex justify-center mt-8 space-x-4">
            <?php if ($movies['popular']['page'] > 1): ?>
                <a href="?page=<?= $movies['popular']['page'] - 1 ?>" 
                   class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Précédent
                </a>
            <?php endif; ?>
            
            <?php if ($movies['popular']['page'] < $movies['popular']['total_pages']): ?>
                <a href="?page=<?= $movies['popular']['page'] + 1 ?>" 
                   class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Suivant
                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Films les Mieux Notés -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Films les Mieux Notés</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($movies['top_rated'] as $movie): ?>
                <div class="relative overflow-hidden rounded-lg group">
                    <?php if ($movie['poster_path']): ?>
                        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                             alt="<?= htmlspecialchars($movie['title']) ?>"
                             class="w-full h-auto transition-transform duration-300 group-hover:scale-105">
                        
                        <!-- Bouton Favoris -->
                        <?php if (isset($_SESSION['user'])): ?>
                            <button onclick="toggleFavorite(event, <?= $movie['id'] ?>, 'movie')" 
                                    class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-10">
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

    <!-- Prochaines Sorties -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Prochaines Sorties</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($movies['upcoming'] as $movie): ?>
                <div class="relative overflow-hidden rounded-lg group">
                    <?php if ($movie['poster_path']): ?>
                        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                             alt="<?= htmlspecialchars($movie['title']) ?>"
                             class="w-full h-auto transition-transform duration-300 group-hover:scale-105">
                        
                        <!-- Bouton Favoris -->
                        <?php if (isset($_SESSION['user'])): ?>
                            <button onclick="toggleFavorite(event, <?= $movie['id'] ?>, 'movie')" 
                                    class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-10">
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

<style>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

.favorite-btn {
    opacity: 0;
    transform: scale(0.8);
}

.favorite-btn i {
    color: #ffffff;
    transition: color 0.3s ease;
}

.favorite-btn.active i {
    color: #e50914;
}

.group:hover .favorite-btn {
    opacity: 1;
    transform: scale(1);
}

.favorite-btn:hover i {
    transform: scale(1.2);
}

.favorite-btn.active {
    opacity: 1;
}

@keyframes heartBeat {
    0% { transform: scale(1); }
    25% { transform: scale(1.2); }
    50% { transform: scale(1); }
    75% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.favorite-btn.animating i {
    animation: heartBeat 0.5s ease-in-out;
}
</style>

<?php require_once 'includes/footer.php'; ?> 