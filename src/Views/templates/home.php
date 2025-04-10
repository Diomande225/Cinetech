<main class="pt-20 bg-black">
    <!-- Featured Content Banner -->
    <section id="featured-banner" class="relative h-[70vh] mb-12 overflow-hidden">
        <?php foreach (array_slice($trendingItems, 0, 5) as $index => $item): ?>
            <div class="banner-item absolute inset-0 <?= $index === 0 ? 'active' : 'hidden' ?>" data-index="<?= $index ?>">
                <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('<?= $item['backdrop_path'] ? (new \App\Services\TMDBApi())->getImageUrl($item['backdrop_path'], 'original') : "./public/img/placeholder_backdrop.jpg" ?>');"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white max-w-2xl">
                    <h1 class="text-5xl font-bold mb-4"><?= htmlspecialchars($item['title'] ?? $item['name']) ?></h1>
                    <p class="text-xl mb-4"><?= substr($item['overview'], 0, 150) ?>...</p>
                    <a href="/Cinetech/detail/<?= $item['media_type'] ?>/<?= $item['id'] ?>" class="inline-block px-6 py-3 bg-red-600 text-white rounded hover:bg-red-700 transition duration-300">Voir Détails</a>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
    
    <!-- Trending Section -->
    <section class="mb-12 px-8">
        <h2 class="text-3xl font-semibold mb-4 text-white">Tendances</h2>
        <div class="flex overflow-x-auto space-x-4 pb-4">
            <?php foreach (array_slice($trendingItems, 0, 10) as $item): ?>
                <div class="flex-none w-48 transition-transform duration-300 hover:scale-105">
                    <img src="<?= $item['poster_path'] ? (new \App\Services\TMDBApi())->getImageUrl($item['poster_path']) : "./public/img/placeholder_trending.jpg" ?>" alt="<?= htmlspecialchars($item['title'] ?? $item['name']) ?>" class="w-full h-72 object-cover rounded">
                    <div class="mt-2">
                        <h3 class="text-lg font-semibold text-white truncate"><?= htmlspecialchars($item['title'] ?? $item['name']) ?></h3>
                        <p class="text-sm text-gray-400"><?= substr($item['release_date'] ?? $item['first_air_date'], 0, 4) ?></p>
                        <a href="/Cinetech/detail/<?= $item['media_type'] ?>/<?= $item['id'] ?>" class="mt-2 text-red-600 hover:underline text-sm">Voir Détails</a>
                        <button class="favorite-button mt-2 text-sm flex items-center" data-item-id="<?= $item['id'] ?>" data-media-type="<?= $item['media_type'] ?>">
                            <i class="<?= in_array($item['id'], $userFavorites ?? []) ? 'fas' : 'far' ?> fa-heart"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Popular Movies Section -->
    <section class="mb-12 px-8">
        <h2 class="text-3xl font-semibold mb-4 text-white">Films Populaires</h2>
        <div class="flex overflow-x-auto space-x-4 pb-4">
            <?php foreach (array_slice($popularMovies, 0, 10) as $movie): ?>
                <div class="flex-none w-48 transition-transform duration-300 hover:scale-105">
                    <img src="<?= $movie['poster_path'] ? (new \App\Services\TMDBApi())->getImageUrl($movie['poster_path']) : "./public/img/placeholder_movie.jpg" ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="w-full h-72 object-cover rounded">
                    <div class="mt-2">
                        <h3 class="text-lg font-semibold text-white truncate"><?= htmlspecialchars($movie['title']) ?></h3>
                        <p class="text-sm text-gray-400"><?= substr($movie['release_date'], 0, 4) ?></p>
                        <a href="/Cinetech/detail/movie/<?= $movie['id'] ?>" class="mt-2 text-red-600 hover:underline text-sm">Voir Détails</a>
                        <button class="favorite-button mt-2 text-sm flex items-center" data-item-id="<?= $movie['id'] ?>" data-media-type="movie">
                            <i class="<?= in_array($movie['id'], $userFavorites ?? []) ? 'fas' : 'far' ?> fa-heart"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Popular TV Series Section -->
    <section class="mb-12 px-8">
        <h2 class="text-3xl font-semibold mb-4 text-white">Séries Populaires</h2>
        <div class="flex overflow-x-auto space-x-4 pb-4">
            <?php foreach (array_slice($popularSeries, 0, 10) as $series): ?>
                <div class="flex-none w-48 transition-transform duration-300 hover:scale-105">
                    <img src="<?= $series['poster_path'] ? (new \App\Services\TMDBApi())->getImageUrl($series['poster_path']) : "./public/img/placeholder_series.jpg" ?>" alt="<?= htmlspecialchars($series['name']) ?>" class="w-full h-72 object-cover rounded">
                    <div class="mt-2">
                        <h3 class="text-lg font-semibold text-white truncate"><?= htmlspecialchars($series['name']) ?></h3>
                        <p class="text-sm text-gray-400"><?= substr($series['first_air_date'], 0, 4) ?></p>
                        <a href="/Cinetech/detail/tv/<?= $series['id'] ?>" class="mt-2 text-red-600 hover:underline text-sm">Voir Détails</a>
                        <button class="favorite-button mt-2 text-sm flex items-center" data-item-id="<?= $series['id'] ?>" data-media-type="tv">
                            <i class="<?= in_array($series['id'], $userFavorites ?? []) ? 'fas' : 'far' ?> fa-heart"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bannerItems = document.querySelectorAll('.banner-item');
    let currentIndex = 0;

    function showNextBanner() {
        bannerItems[currentIndex].classList.add('hidden');
        bannerItems[currentIndex].classList.remove('active');
        
        currentIndex = (currentIndex + 1) % bannerItems.length;
        
        bannerItems[currentIndex].classList.remove('hidden');
        bannerItems[currentIndex].classList.add('active');
    }

    setInterval(showNextBanner, 10000); // Change toutes les 10 secondes
});
</script>