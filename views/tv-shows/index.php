<?php 
$pageTitle = 'Séries TV - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="container mx-auto px-4 pt-32">
    <!-- Séries Populaires -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Séries Populaires</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($shows['results'] as $show): ?>
                <div class="relative group">
                    <img src="https://image.tmdb.org/t/p/w500<?= $show['poster_path'] ?>" 
                         alt="<?= htmlspecialchars($show['name']) ?>"
                         class="w-full h-auto rounded-lg">
                    
                    <?php if (isset($_SESSION['user'])): ?>
                        <button onclick="toggleFavorite(event, <?= $show['id'] ?>, 'tv')" 
                                class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-10">
                            <i class="fas fa-heart <?= isFavorite($show['id'], 'tv') ? 'text-red-600' : 'text-white' ?>"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8 space-x-4">
            <?php if ($shows['page'] > 1): ?>
                <a href="?page=<?= $shows['page'] - 1 ?>" 
                   class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Précédent
                </a>
            <?php endif; ?>
            
            <?php if ($shows['page'] < $shows['total_pages']): ?>
                <a href="?page=<?= $shows['page'] + 1 ?>" 
                   class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Suivant
                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Meilleures Notes -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Meilleures Notes</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($topRated as $show): ?>
                <a href="/Cinetech/tv/<?= $show['id'] ?>" class="group">
                    <div class="relative overflow-hidden rounded-lg">
                        <?php if ($show['poster_path']): ?>
                            <img src="https://image.tmdb.org/t/p/w500<?= $show['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($show['name']) ?>"
                                 class="w-full h-auto transition-transform duration-300 group-hover:scale-110">
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 p-4">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($show['name']) ?></h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    <span><?= number_format($show['vote_average'], 1) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Diffusé Aujourd'hui -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Diffusé Aujourd'hui</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($airingToday as $show): ?>
                <a href="/Cinetech/tv/<?= $show['id'] ?>" class="group">
                    <div class="relative overflow-hidden rounded-lg">
                        <?php if ($show['poster_path']): ?>
                            <img src="https://image.tmdb.org/t/p/w500<?= $show['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($show['name']) ?>"
                                 class="w-full h-auto transition-transform duration-300 group-hover:scale-110">
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 p-4">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($show['name']) ?></h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    <span><?= number_format($show['vote_average'], 1) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
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
</style>

<?php require_once 'includes/footer.php'; ?> 