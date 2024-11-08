<?php 
$pageTitle = $movie['title'] . ' - La Cinétech';
require_once 'includes/header.php'; 
?>

<div class="container mx-auto px-4 pt-32">
    <div class="flex flex-col md:flex-row">
        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?>" 
             class="w-full md:w-1/3 rounded-lg shadow-lg">
        <div class="md:ml-8 mt-4 md:mt-0">
            <h1 class="text-4xl font-bold"><?= htmlspecialchars($movie['title']) ?></h1>
            <p class="mt-4"><?= htmlspecialchars($movie['overview']) ?></p>
            <div class="mt-4">
                <button onclick="playTrailer('<?= $trailer['key'] ?>')" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-play mr-2"></i> Bande annonce
                </button>
            </div>
            <h2 class="text-2xl font-bold mt-8">Acteurs</h2>
            <ul>
                <?php foreach ($credits['cast'] as $actor): ?>
                    <li><?= htmlspecialchars($actor['name']) ?> as <?= htmlspecialchars($actor['character']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
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