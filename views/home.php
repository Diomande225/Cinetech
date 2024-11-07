<?php 
$pageTitle = 'Accueil - La Cinétech';
require_once 'includes/header.php'; 
?>

<!-- Hero Banner -->
<div class="relative h-screen">
    <!-- Image de fond -->
    <div class="absolute inset-0">
        <?php if (isset($content['movieDetails']['backdrop_path'])): ?>
            <img src="https://image.tmdb.org/t/p/original<?= $content['movieDetails']['backdrop_path'] ?>" 
                 alt="<?= htmlspecialchars($content['movieDetails']['title'] ?? '') ?>"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
        <?php endif; ?>
    </div>

    <!-- Contenu du banner -->
    <div class="relative z-10 h-full flex items-center">
        <div class="container mx-auto px-12 pt-32">
            <h1 class="text-7xl font-bold mb-4 max-w-2xl">
                <?= htmlspecialchars($content['movieDetails']['title'] ?? '') ?>
            </h1>
            <div class="flex items-center space-x-4 mb-4">
                <span class="text-sm">
                    <?= isset($content['movieDetails']['release_date']) ? date('Y', strtotime($content['movieDetails']['release_date'])) : '' ?>
                </span>
                <?php if (isset($content['movieDetails']['vote_average'])): ?>
                    <span class="flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        <?= number_format($content['movieDetails']['vote_average'], 1) ?>
                    </span>
                <?php endif; ?>
            </div>
            <p class="text-xl max-w-2xl mb-8 text-gray-200">
                <?= htmlspecialchars($content['movieDetails']['overview'] ?? '') ?>
            </p>
            <div class="flex space-x-4">
                <a href="/movie/<?= $content['movieDetails']['id'] ?? '' ?>" 
                   class="flex items-center bg-white text-black px-8 py-3 rounded hover:bg-opacity-80 transition">
                    <i class="fas fa-play mr-2"></i> Lecture
                </a>
                <button class="flex items-center bg-gray-500 bg-opacity-50 px-8 py-3 rounded hover:bg-opacity-40 transition">
                    <i class="fas fa-info-circle mr-2"></i> Plus d'infos
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sections de contenu -->
<div class="container mx-auto px-4 py-8">
    <!-- Films Populaires -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Films Populaires</h2>
        <div class="relative">
            <div class="flex overflow-x-scroll hide-scrollbar gap-4">
                <?php foreach ($content['categories']['popular_movies'] as $movie): ?>
                    <div class="flex-none w-[200px]">
                        <div class="relative group">
                            <!-- Bouton Favoris -->
                            <button onclick="toggleFavorite(event, <?= $movie['id'] ?>, 'movie')" 
                                    class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-20
                                           <?= isset($_SESSION['user']) && isInFavorites($movie['id'], 'movie') ? 'active' : '' ?>">
                                <i class="fas fa-heart text-xl"></i>
                            </button>

                            <!-- Lien cliquable -->
                            <a href="/Cinetech/movie/<?= $movie['id'] ?>" class="block relative z-10">
                                <?php if ($movie['poster_path']): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                                         alt="<?= htmlspecialchars($movie['title']) ?>"
                                         class="w-full h-auto rounded-lg transition-transform duration-300 group-hover:scale-105">
                                <?php endif; ?>

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                    <div class="absolute bottom-0 p-4">
                                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($movie['title']) ?></h3>
                                        <div class="flex items-center mt-2">
                                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                                            <span><?= number_format($movie['vote_average'], 1) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Séries Populaires -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Séries Populaires</h2>
        <div class="relative">
            <div class="flex overflow-x-scroll hide-scrollbar gap-4">
                <?php foreach ($content['categories']['popular_tv'] as $show): ?>
                    <div class="flex-none w-[200px]">
                        <div class="relative group">
                            <!-- Bouton Favoris -->
                            <button onclick="toggleFavorite(event, <?= $show['id'] ?>, 'tv')" 
                                    class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-20
                                           <?= isset($_SESSION['user']) && isInFavorites($show['id'], 'tv') ? 'active' : '' ?>">
                                <i class="fas fa-heart text-xl"></i>
                            </button>

                            <!-- Lien cliquable -->
                            <a href="/Cinetech/tv/<?= $show['id'] ?>" class="block relative z-10">
                                <?php if ($show['poster_path']): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $show['poster_path'] ?>" 
                                         alt="<?= htmlspecialchars($show['name']) ?>"
                                         class="w-full h-auto rounded-lg transition-transform duration-300 group-hover:scale-105">
                                <?php endif; ?>

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                    <div class="absolute bottom-0 p-4">
                                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($show['name']) ?></h3>
                                        <div class="flex items-center mt-2">
                                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                                            <span><?= number_format($show['vote_average'], 1) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Films les Mieux Notés -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Films les Mieux Notés</h2>
        <div class="relative">
            <div class="flex overflow-x-scroll hide-scrollbar gap-4">
                <?php foreach ($content['categories']['top_rated_movies'] as $movie): ?>
                    <div class="flex-none w-[200px]">
                        <div class="relative group">
                            <a href="/Cinetech/movie/<?= $movie['id'] ?>">
                                <?php if ($movie['poster_path']): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                                         alt="<?= htmlspecialchars($movie['title']) ?>"
                                         class="w-full h-auto rounded-lg transition-transform duration-300 group-hover:scale-105">
                                <?php endif; ?>
                            </a>

                            <!-- Bouton Favoris -->
                            <button onclick="toggleFavorite(event, <?= $movie['id'] ?>, 'movie')" 
                                    class="favorite-btn absolute top-2 right-2 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center transition-all hover:bg-black/75 z-10
                                           <?= isset($_SESSION['user']) && isInFavorites($movie['id'], 'movie') ? 'active' : '' ?>">
                                <i class="fas fa-heart text-xl"></i>
                            </button>

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                <div class="absolute bottom-0 p-4">
                                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($movie['title']) ?></h3>
                                    <div class="flex items-center mt-2">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                        <span><?= number_format($movie['vote_average'], 1) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<?php require_once 'includes/footer.php'; ?> 