<!-- detail.php -->
<main class="pt-20 container mx-auto px-4 detail-page">
    <div class="flex flex-col md:flex-row">
        <div class="w-full md:w-1/3 mb-4 md:mb-0">
            <img src="<?= $details['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $details['poster_path'] : "./public/img/placeholder.jpg" ?>" alt="<?= htmlspecialchars($details['title'] ?? $details['name']) ?>" class="w-full rounded-lg shadow-lg mx-auto max-w-xs md:max-w-full">
        </div>
        <div class="w-full md:w-2/3 md:pl-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-4"><?= htmlspecialchars($details['title'] ?? $details['name']) ?></h1>
            
            <!-- Synopsis traduit -->
            <div class="mb-6">
                <h3 class="text-lg md:text-xl font-semibold mb-2 flex items-center">
                    <?= __('overview') ?>
                    <span class="ml-2 text-gray-400 text-sm" title="Texte traduit de l'anglais">
                        <i class="fas fa-language"></i>
                    </span>
                </h3>
                <p class="text-gray-300 text-sm md:text-base"><?= htmlspecialchars(translateWithCache($details['overview'])) ?></p>
            </div>
            
            <!-- Informations avec labels traduits -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <p class="mb-2"><strong><?= $mediaType === 'movie' ? __('release_date') : __('first_air_date') ?>:</strong> 
                    <?= $mediaType === 'movie' ? $details['release_date'] : $details['first_air_date'] ?>
                </p>
                
                <p class="mb-2"><strong><?= __('rating') ?>:</strong> <?= number_format($details['vote_average'], 1) ?>/10</p>
                
                <p class="mb-2"><strong><?= __('genres') ?>:</strong> <?= implode(', ', array_column($details['genres'], 'name')) ?></p>
                
                <?php if ($mediaType === 'tv'): ?>
                    <p class="mb-2"><strong><?= __('number_of_seasons') ?>:</strong> <?= $details['number_of_seasons'] ?></p>
                    <p class="mb-2"><strong><?= __('number_of_episodes') ?>:</strong> <?= $details['number_of_episodes'] ?></p>
                <?php endif; ?>
            </div>
            
            <button class="favori-button <?= $isFavori ? 'active' : '' ?>" 
                    data-item-id="<?= $details['id'] ?>" 
                    data-media-type="<?= $mediaType ?>">
                <i class="<?= $isFavori ? 'fas' : 'far' ?> fa-heart"></i>
            </button>
        </div>
    </div>

    <!-- Casting -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4"><?= __('cast') ?></h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach (array_slice($details['credits']['cast'], 0, 6) as $actor): ?>
                <div class="bg-black rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-transform duration-300 hover:scale-105">
                    <a href="<?php echo $basePath; ?>/actor/<?= $actor['id'] ?>" class="block">
                        <img src="<?= $actor['profile_path'] ? "https://image.tmdb.org/t/p/w185" . $actor['profile_path'] : "./public/img/placeholder_actor.jpg" ?>" alt="<?= htmlspecialchars($actor['name']) ?>" class="w-full h-40 object-cover">
                        <div class="p-2">
                            <p class="font-semibold text-sm text-white hover:text-red-600"><?= htmlspecialchars($actor['name']) ?></p>
                            <p class="text-xs text-gray-400"><?= htmlspecialchars(translateWithCache($actor['character'])) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bande-annonce -->
    <?php if (!empty($details['videos']['results'])): ?>
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Trailer</h2>
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

    <!-- Similaires -->
    <?php if (!empty($details['similar']['results'])): ?>
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4"><?= __('similar') ?></h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach (array_slice($details['similar']['results'], 0, 6) as $similar): ?>
                    <div class="bg-black rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-transform duration-300 hover:scale-105">
                        <a href="<?php echo $basePath; ?>/detail/<?= $mediaType ?>/<?= $similar['id'] ?>" class="block">
                            <img src="<?= $similar['poster_path'] ? "https://image.tmdb.org/t/p/w185" . $similar['poster_path'] : "./public/img/placeholder.jpg" ?>" alt="<?= htmlspecialchars($similar['title'] ?? $similar['name']) ?>" class="w-full h-40 object-cover">
                            <div class="p-2">
                                <p class="font-semibold text-sm text-white hover:text-red-600">
                                    <?= htmlspecialchars($similar['title'] ?? $similar['name']) ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Commentaires -->
    <?php if (isset($details) && isset($mediaType)): ?>
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4 text-white"><?= __('comments') ?></h2>
            <?php 
                $commentController = new \App\Controllers\CommentController();
                echo $commentController->getCommentsHtml($details['id'], $mediaType);
            ?>
        </div>
    <?php endif; ?>

</main>

<script src="<?php echo $basePath; ?>/js/favoris.js"></script>