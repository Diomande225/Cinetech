<main class="pt-20 container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6 text-white">Films</h1>

    <!-- Filtres -->
    <form action="/Cinetech/movies" method="GET" class="mb-8">
        <div class="flex space-x-4">
            <select name="genre" class="bg-gray-700 text-white rounded-md p-2">
                <option value="">Tous les genres</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>" <?= $currentGenre == $genre['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($genre['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="year" class="bg-gray-700 text-white rounded-md p-2">
                <option value="">Toutes les années</option>
                <?php for ($i = date('Y'); $i >= 1900; $i--): ?>
                    <option value="<?= $i ?>" <?= $currentYear == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            <select name="sort" class="bg-gray-700 text-white rounded-md p-2">
                <option value="popularity.desc" <?= $currentSort == 'popularity.desc' ? 'selected' : '' ?>>Les plus populaires</option>
                <option value="vote_average.desc" <?= $currentSort == 'vote_average.desc' ? 'selected' : '' ?>>Les mieux notés</option>
                <option value="release_date.desc" <?= $currentSort == 'release_date.desc' ? 'selected' : '' ?>>Les plus récents</option>
            </select>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md">Filtrer</button>
        </div>
    </form>

    <!-- Grille de films -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <?php foreach ($movies as $movie): ?>
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="/Cinetech/detail/movie/<?= $movie['id'] ?>" class="block">
                    <img src="<?= $movie['poster_path'] ? (new \App\Services\TMDBApi())->getImageUrl($movie['poster_path'], 'w500') : './public/img/placeholder_poster.jpg' ?>" 
                         alt="<?= htmlspecialchars($movie['title']) ?>" 
                         class="w-full h-auto object-cover">
                
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white mb-2"><?= htmlspecialchars($movie['title']) ?></h3>
                        <p class="text-gray-400 text-sm mb-2"><?= date('Y', strtotime($movie['release_date'])) ?></p>
                        <p class="text-gray-400 text-sm mb-2">Note: <?= number_format($movie['vote_average'], 1) ?>/10</p>
                    </div>
                </a>
                <div class="px-4 pb-4 flex justify-between items-center">
                    <a href="/Cinetech/detail/movie/<?= $movie['id'] ?>" class="text-red-600 hover:underline text-sm">Voir Détails</a>
                    <button class="favori-button text-sm flex items-center" data-item-id="<?= $movie['id'] ?>" data-media-type="movie">
                        <i class="<?= in_array($movie['id'], $userFavorites ?? []) ? 'fas' : 'far' ?> fa-heart"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="/Cinetech/js/favoris.js"></script>