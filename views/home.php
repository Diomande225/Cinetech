<?php 
$pageTitle = 'Accueil - La Cinétech';
require_once 'includes/header.php'; 
?>

<!-- Hero Banner -->
<div class="relative h-screen">
    <div class="absolute inset-0">
        <?php if (isset($featured['backdrop_path'])): ?>
            <img src="https://image.tmdb.org/t/p/original<?= $featured['backdrop_path'] ?>" 
                 alt="<?= htmlspecialchars($featured['title'] ?? $featured['name']) ?>"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
        <?php endif; ?>
    </div>

    <div class="relative z-10 h-full flex items-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    <?= htmlspecialchars($featured['title'] ?? $featured['name']) ?>
                </h1>
                <p class="text-lg text-gray-300 mb-6">
                    <?= htmlspecialchars($featured['overview'] ?? '') ?>
                </p>
                <div class="flex space-x-4">
                    <?php if (isset($featured['trailer_key'])): ?>
                        <button onclick="playTrailer('<?= $featured['trailer_key'] ?>')"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition">
                            <i class="fas fa-play mr-2"></i> Bande annonce
                        </button>
                    <?php endif; ?>
                    <a href="/<?= $featured['media_type'] ?>/<?= $featured['id'] ?>"
                       class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition">
                        Plus d'infos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sections de contenu -->
<div class="bg-gray-900 -mt-20 relative z-10">
    <div class="container mx-auto px-4 py-16">
        <!-- Tendances -->
        <?php if (!empty($content['trending'])): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Tendances</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach ($content['trending'] as $item): ?>
                    <div class="relative group">
                        <a href="/<?= $item['media_type'] ?>/<?= $item['id'] ?>">
                            <img src="https://image.tmdb.org/t/p/w500<?= $item['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($item['title'] ?? $item['name']) ?>"
                                 class="rounded-lg transition duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 rounded-lg">
                                <div class="absolute bottom-0 p-4 w-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <h3 class="text-white font-semibold">
                                        <?= htmlspecialchars($item['title'] ?? $item['name']) ?>
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Films Populaires -->
        <?php if (!empty($content['popular_movies'])): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Films Populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach ($content['popular_movies'] as $movie): ?>
                    <div class="relative group">
                        <a href="/movie/<?= $movie['id'] ?>">
                            <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($movie['title']) ?>"
                                 class="rounded-lg transition duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 rounded-lg">
                                <div class="absolute bottom-0 p-4 w-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <h3 class="text-white font-semibold">
                                        <?= htmlspecialchars($movie['title']) ?>
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Séries Populaires -->
        <?php if (!empty($content['popular_shows'])): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Séries Populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach ($content['popular_shows'] as $show): ?>
                    <div class="relative group">
                        <a href="/tv/<?= $show['id'] ?>">
                            <img src="https://image.tmdb.org/t/p/w500<?= $show['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($show['name']) ?>"
                                 class="rounded-lg transition duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 rounded-lg">
                                <div class="absolute bottom-0 p-4 w-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <h3 class="text-white font-semibold">
                                        <?= htmlspecialchars($show['name']) ?>
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>

<!-- Modal pour la bande-annonce -->
<div id="trailerModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/90"></div>
    <div class="relative z-10 w-full h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-5xl">
            <button class="absolute -top-12 right-0 text-white text-xl" onclick="closeTrailer()">
                <i class="fas fa-times"></i>
            </button>
            <div class="aspect-w-16 aspect-h-9">
                <iframe id="trailerFrame" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<script>
function playTrailer(key) {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = `https://www.youtube.com/embed/${key}?autoplay=1`;
    modal.classList.remove('hidden');
}

function closeTrailer() {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = '';
    modal.classList.add('hidden');
}
</script>

<?php require_once 'includes/footer.php'; ?> 