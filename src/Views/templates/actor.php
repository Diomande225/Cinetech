<!-- actor.php -->
<main class="pt-20 container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($actor['name']) ?></h1>

    <div class="flex flex-col md:flex-row mb-8">
        <img src="<?= $actor['profile_path'] ? "https://image.tmdb.org/t/p/w300" . $actor['profile_path'] : $basePath . "/public/img/placeholder_actor.jpg" ?>" alt="<?= htmlspecialchars($actor['name']) ?>" class="w-64 h-auto rounded-lg">
        <div class="md:ml-8 mt-4 md:mt-0">
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2 flex items-center">
                    <?= __('biography') ?>
                    <span class="ml-2 text-gray-400 text-sm" title="Texte traduit de l'anglais">
                        <i class="fas fa-language"></i>
                    </span>
                </h3>
                <p class="text-gray-300"><?= htmlspecialchars($actor['biography_translated'] ?? $actor['biography']) ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="mb-2"><strong><?= __('birth_date') ?>:</strong> <?= $actor['birthday'] ?></p>
                <?php if (!empty($actor['deathday'])): ?>
                    <p class="mb-2"><strong><?= __('death_date') ?>:</strong> <?= $actor['deathday'] ?></p>
                <?php endif; ?>
                <p class="mb-2"><strong><?= __('place_of_birth') ?>:</strong> <?= htmlspecialchars($actor['place_of_birth']) ?></p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-4"><?= __('filmography') ?></h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <?php foreach ($credits['cast'] as $credit): ?>
            <div class="bg-black rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-transform duration-300 hover:scale-105">
                <a href="<?php echo $basePath; ?>/detail/<?= $credit['media_type'] ?>/<?= $credit['id'] ?>" class="block">
                    <img src="<?= $credit['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $credit['poster_path'] : $basePath . "/public/img/placeholder_movie.jpg" ?>" alt="<?= htmlspecialchars($credit['title'] ?? $credit['name']) ?>" class="w-full h-60 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($credit['title'] ?? $credit['name']) ?></h3>
                        <p class="text-sm mt-2 text-gray-400"><?= __('role') ?>: <?= htmlspecialchars(translateExternal($credit['character'])) ?></p>
                    </div>
                </a>
                <div class="px-4 pb-4 flex justify-end items-center">
                    <button class="favori-button text-sm flex items-center" data-item-id="<?= $credit['id'] ?>" data-media-type="<?= $credit['media_type'] ?>">
                        <i class="<?= in_array($credit['id'], $userFavorites ?? []) ? 'fas' : 'far' ?> fa-heart"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="<?php echo $basePath; ?>/js/favoris.js"></script>