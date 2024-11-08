<?php 
$pageTitle = 'Mes Favoris - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="container mx-auto px-4 pt-32">
    <h1 class="text-3xl font-bold mb-8">Mes Favoris</h1>

    <?php if (empty($favorites)): ?>
        <div class="text-center py-8">
            <p class="text-xl mb-4">Vous n'avez pas encore de favoris.</p>
            <div class="space-x-4">
                <a href="/Cinetech/movies" class="inline-block px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Découvrir des films
                </a>
                <a href="/Cinetech/tv-shows" class="inline-block px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Découvrir des séries
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($favorites as $favorite): ?>
                <div class="relative group">
                    <img src="https://image.tmdb.org/t/p/w500<?= $favorite['poster_path'] ?>" 
                         alt="<?= htmlspecialchars($favorite['title'] ?? $favorite['name']) ?>"
                         class="w-full h-auto rounded-lg">
                    
                    <div class="absolute inset-0 bg-black bg-opacity-75 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-white text-lg font-semibold mb-2">
                                <?= htmlspecialchars($favorite['title'] ?? $favorite['name']) ?>
                            </h3>
                            <div class="flex justify-between items-center">
                                <a href="/Cinetech/<?= $favorite['media_type'] === 'movie' ? 'movies' : 'tv-shows' ?>/<?= $favorite['media_id'] ?>" 
                                   class="text-white hover:text-red-600">
                                    Voir plus
                                </a>
                                <button onclick="toggleFavorite(event, <?= $favorite['media_id'] ?>, '<?= $favorite['media_type'] ?>')" 
                                        class="text-red-600 hover:text-white">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?> 