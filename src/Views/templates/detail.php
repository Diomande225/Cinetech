<!-- detail.php -->
<main class="pt-20 container mx-auto px-4">
    <div class="flex flex-col md:flex-row">
        <div class="md:w-1/3">
            <img src="<?= $details['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $details['poster_path'] : "./public/img/placeholder.jpg" ?>" alt="<?= htmlspecialchars($details['title'] ?? $details['name']) ?>" class="w-full rounded-lg shadow-lg">
        </div>
        <div class="md:w-2/3 md:pl-8 mt-4 md:mt-0">
            <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($details['title'] ?? $details['name']) ?></h1>
            <p class="text-gray-300 mb-4"><?= htmlspecialchars($details['overview']) ?></p>
            <p class="mb-2"><strong>Date de sortie:</strong> <?= $mediaType === 'movie' ? $details['release_date'] : $details['first_air_date'] ?></p>
            <p class="mb-2"><strong>Note:</strong> <?= number_format($details['vote_average'], 1) ?>/10</p>
            <p class="mb-2"><strong>Genres:</strong> <?= implode(', ', array_column($details['genres'], 'name')) ?></p>
            <?php if ($mediaType === 'tv'): ?>
                <p class="mb-2"><strong>Nombre de saisons:</strong> <?= $details['number_of_seasons'] ?></p>
                <p class="mb-2"><strong>Nombre d'épisodes:</strong> <?= $details['number_of_episodes'] ?></p>
            <?php endif; ?>
            
            <button class="favori-button <?= $isFavori ? 'active' : '' ?>" 
                    data-item-id="<?= $id ?>" 
                    data-media-type="<?= $mediaType ?>">
                <i class="<?= $isFavori ? 'fas' : 'far' ?> fa-heart"></i>
            </button>
        </div>
    </div>

    <!-- Casting -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Casting</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach (array_slice($details['credits']['cast'], 0, 6) as $actor): ?>
                <div class="bg-gray-700 rounded-lg overflow-hidden shadow-md">
                    <img src="<?= $actor['profile_path'] ? "https://image.tmdb.org/t/p/w185" . $actor['profile_path'] : "./public/img/placeholder_actor.jpg" ?>" alt="<?= htmlspecialchars($actor['name']) ?>" class="w-full h-40 object-cover">
                    <div class="p-2">
                        <a href="/actor/<?= $actor['id'] ?>" class="font-semibold text-sm hover:text-red-600"><?= htmlspecialchars($actor['name']) ?></a>
                        <p class="text-xs text-gray-400"><?= htmlspecialchars($actor['character']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bande-annonce -->
    <?php if (!empty($details['videos']['results'])): ?>
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Bande-annonce</h2>
            <?php
            $trailer = array_filter($details['videos']['results'], function($video) {
                return $video['type'] === 'Trailer' && $video['site'] === 'YouTube';
            });
            $trailer = reset($trailer);
            if ($trailer):
            ?>
                <div class="relative mx-auto" style="max-width: 800px; padding-top: 50%; /* Aspect ratio 16:8 */">
                    <iframe 
                        src="https://www.youtube.com/embed/<?= $trailer['key'] ?>" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                        class="absolute top-0 left-0 w-full h-full"
                    ></iframe>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php
// Ajoutez ceci au début du fichier
error_log("=== Variables dans detail.php ===");
error_log("mediaType: " . ($mediaType ?? 'non défini'));
error_log("details['id']: " . ($details['id'] ?? 'non défini'));
?>

<?php if (isset($details) && isset($mediaType)): ?>
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4 text-white">Commentaires</h2>
        <?php 
            $commentController = new \App\Controllers\CommentController();
            echo $commentController->getCommentsHtml($details['id'], $mediaType);
        ?>
    </div>
<?php endif; ?>

</main>

<script src="/Cinetech/js/favoris.js"></script>