<!-- actor.php -->
<main class="pt-20 container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($actor['name']) ?></h1>

    <div class="flex mb-8">
        <img src="<?= $actor['profile_path'] ? "https://image.tmdb.org/t/p/w300" . $actor['profile_path'] : $basePath . "/public/img/placeholder_actor.jpg" ?>" alt="<?= htmlspecialchars($actor['name']) ?>" class="w-64 h-auto rounded-lg">
        <div class="ml-8">
            <p class="mb-4"><?= htmlspecialchars($actor['biography']) ?></p>
            <p><strong>Date de naissance:</strong> <?= $actor['birthday'] ?></p>
            <p><strong>Lieu de naissance:</strong> <?= htmlspecialchars($actor['place_of_birth']) ?></p>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-4">Filmographie</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <?php foreach ($credits['cast'] as $credit): ?>
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-transform duration-300 hover:scale-105">
                <a href="<?php echo $basePath; ?>/detail/<?= $credit['media_type'] ?>/<?= $credit['id'] ?>" class="block">
                    <img src="<?= $credit['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $credit['poster_path'] : $basePath . "/public/img/placeholder_movie.jpg" ?>" alt="<?= htmlspecialchars($credit['title'] ?? $credit['name']) ?>" class="w-full h-60 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($credit['title'] ?? $credit['name']) ?></h3>
                        <p class="text-sm mt-2 text-gray-400">Rôle: <?= htmlspecialchars($credit['character']) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>