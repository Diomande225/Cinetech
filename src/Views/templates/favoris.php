<main class="pt-20 container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Mes Favoris</h1>

    <?php if (empty($favoris)): ?>
        <p class="text-white">Vous n'avez pas encore ajouté de favoris.</p>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($favoris as $favori): ?>
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <img src="<?= $favori['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $favori['poster_path'] : "./public/img/placeholder.jpg" ?>" alt="<?= htmlspecialchars($favori['title'] ?? $favori['name']) ?>" class="w-full h-60 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold"><?= htmlspecialchars($favori['title'] ?? $favori['name']) ?></h3>
                        <p class="text-sm mt-2">Type: <?= htmlspecialchars($favori['media_type']) ?></p>
                        <a href="/Cinetech/detail/<?= $favori['media_type'] ?>/<?= $favori['item_id'] ?>" class="block mt-4 text-red-600 hover:underline">Voir Détails</a>
                        <button class="favorite-button mt-2 text-sm flex items-center" data-item-id="<?= $favori['item_id'] ?>" data-media-type="<?= $favori['media_type'] ?>">
                            <i class="fas fa-heart"></i> Retirer des Favoris
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="/Cinetech/public/js/favoris.js"></script>