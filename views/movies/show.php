<?php require_once 'includes/header.php'; ?>

<!-- Modal Trailer -->
<div id="trailerModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-75"></div>
    <div class="relative z-10 w-full h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-black rounded-lg">
            <button class="absolute -top-10 right-0 text-white text-xl" onclick="closeTrailer()">
                <i class="fas fa-times"></i>
            </button>
            <div class="aspect-w-16 aspect-h-9">
                <iframe id="trailerFrame" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Hero Section -->
<div class="relative min-h-screen">
    <!-- Backdrop Image -->
    <div class="absolute inset-0">
        <img src="https://image.tmdb.org/t/p/original<?= $movie['backdrop_path'] ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?>"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-transparent"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 container mx-auto px-4 pt-32">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Poster -->
            <div class="w-64 flex-shrink-0">
                <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                     alt="<?= htmlspecialchars($movie['title']) ?>"
                     class="w-full rounded-lg shadow-lg">
            </div>

            <!-- Info -->
            <div class="flex-1">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    <?= htmlspecialchars($movie['title']) ?>
                </h1>

                <div class="flex items-center space-x-4 mb-6">
                    <span class="text-sm"><?= date('Y', strtotime($movie['release_date'])) ?></span>
                    <span class="text-sm"><?= $movie['runtime'] ?> min</span>
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        <span><?= number_format($movie['vote_average'], 1) ?></span>
                    </div>
                </div>

                <div class="flex space-x-4 mb-8">
                    <?php if (!empty($videos)): ?>
                        <button onclick="playTrailer('<?= $videos[0]['key'] ?>')" 
                                class="flex items-center bg-white text-black px-6 py-2 rounded hover:bg-opacity-80 transition">
                            <i class="fas fa-play mr-2"></i> Bande annonce
                        </button>
                    <?php endif; ?>
                    
                    <button onclick="toggleFavorite(<?= $movie['id'] ?>)" 
                            class="flex items-center bg-gray-600 bg-opacity-50 px-6 py-2 rounded hover:bg-opacity-70 transition">
                        <i class="fas <?= $isFavorite ? 'fa-check' : 'fa-plus' ?> mr-2"></i>
                        <?= $isFavorite ? 'Dans ma liste' : 'Ajouter à ma liste' ?>
                    </button>
                </div>

                <p class="text-lg mb-8"><?= htmlspecialchars($movie['overview']) ?></p>

                <!-- Genres -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-2">Genres</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($movie['genres'] as $genre): ?>
                            <span class="px-3 py-1 bg-gray-800 rounded-full text-sm">
                                <?= htmlspecialchars($genre['name']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Cast -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Casting principal</h3>
                    <div class="flex overflow-x-auto space-x-4 pb-4">
                        <?php foreach (array_slice($movie['credits']['cast'], 0, 6) as $actor): ?>
                            <div class="flex-none w-32">
                                <img src="https://image.tmdb.org/t/p/w200<?= $actor['profile_path'] ?>" 
                                     alt="<?= htmlspecialchars($actor['name']) ?>"
                                     class="w-full h-48 object-cover rounded-lg mb-2">
                                <p class="text-sm font-semibold"><?= htmlspecialchars($actor['name']) ?></p>
                                <p class="text-sm text-gray-400"><?= htmlspecialchars($actor['character']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Similar Movies -->
<section class="py-12 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">Films similaires</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach (array_slice($similar, 0, 6) as $similarMovie): ?>
                <a href="/movie/<?= $similarMovie['id'] ?>" class="block group">
                    <div class="relative aspect-[2/3] rounded-lg overflow-hidden">
                        <img src="https://image.tmdb.org/t/p/w500<?= $similarMovie['poster_path'] ?>" 
                             alt="<?= htmlspecialchars($similarMovie['title']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold">
                        <?= htmlspecialchars($similarMovie['title']) ?>
                    </h3>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

<script>
function playTrailer(videoId) {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    modal.classList.remove('hidden');
}

function closeTrailer() {
    const modal = document.getElementById('trailerModal');
    const frame = document.getElementById('trailerFrame');
    frame.src = '';
    modal.classList.add('hidden');
}

async function toggleFavorite(movieId) {
    try {
        const response = await fetch('/api/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ movie_id: movieId })
        });
        
        const data = await response.json();
        const button = event.target.closest('button');
        const icon = button.querySelector('i');
        
        if (data.status === 'added') {
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-check');
            button.querySelector('span').textContent = 'Dans ma liste';
        } else {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-plus');
            button.querySelector('span').textContent = 'Ajouter à ma liste';
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Fermer le modal avec Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeTrailer();
});
</script>

<?php require_once 'includes/footer.php'; ?> 