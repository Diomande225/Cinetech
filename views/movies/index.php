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
                        <a href="/movie/<?= $movie['id'] ?>" class="group">
                            <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="rounded-lg transition duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-0 p-4">
                                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($movie['title']) ?></h3>
                                    <div class="flex items-center mt-2">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                        <span><?= number_format($movie['vote_average'], 1) ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?> 