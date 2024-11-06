<?php require_once 'includes/header.php'; ?>

<!-- Hero Banner -->
<div class="relative h-screen">
    <!-- Image de fond -->
    <div class="absolute inset-0">
        <img src="https://image.tmdb.org/t/p/original<?= $movieDetails['backdrop_path'] ?>" 
             alt="<?= htmlspecialchars($movieDetails['title']) ?>"
             class="w-full h-full object-cover">
        <!-- Overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
    </div>

    <!-- Contenu du banner -->
    <div class="relative z-10 h-full flex items-center">
        <div class="container mx-auto px-12 pt-32">
            <h1 class="text-7xl font-bold mb-4 max-w-2xl">
                <?= htmlspecialchars($movieDetails['title']) ?>
            </h1>
            <p class="text-xl max-w-2xl mb-8 text-gray-200">
                <?= htmlspecialchars($movieDetails['overview']) ?>
            </p>
            <div class="flex space-x-4">
                <button class="flex items-center bg-white text-black px-8 py-3 rounded hover:bg-opacity-80 transition">
                    <i class="fas fa-play mr-2"></i> Lecture
                </button>
                <button class="flex items-center bg-gray-500 bg-opacity-50 px-8 py-3 rounded hover:bg-opacity-40 transition">
                    <i class="fas fa-info-circle mr-2"></i> Plus d'infos
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sections de contenu -->
<div class="relative z-10 -mt-32 pb-12 bg-gradient-to-b from-transparent to-black">
    <!-- Tendances -->
    <section class="slider-section mb-12">
        <h2 class="text-2xl font-bold mb-4 px-12">Tendances actuelles</h2>
        <div class="relative group">
            <div class="flex overflow-x-auto hide-scrollbar px-12 space-x-4">
                <?php foreach ($categories['trending']['results'] as $item): ?>
                    <div class="flex-none w-[250px]">
                        <div class="relative group/item transition-transform duration-300 hover:scale-110 hover:z-10">
                            <img src="https://image.tmdb.org/t/p/w500<?= $item['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($item['title'] ?? $item['name']) ?>"
                                 class="rounded-md w-full">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover/item:opacity-50 transition-opacity rounded-md"></div>
                            <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                <h3 class="text-sm font-bold"><?= htmlspecialchars($item['title'] ?? $item['name']) ?></h3>
                                <div class="flex items-center space-x-2 mt-2">
                                    <button class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center hover:bg-opacity-80">
                                        <i class="fas fa-play text-sm"></i>
                                    </button>
                                    <button class="w-8 h-8 rounded-full border border-white flex items-center justify-center hover:bg-white hover:text-black">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Films populaires -->
    <section class="slider-section mb-12">
        <h2 class="text-2xl font-bold mb-4 px-12">Films populaires</h2>
        <div class="relative group">
            <div class="flex overflow-x-auto hide-scrollbar px-12 space-x-4">
                <?php foreach ($categories['popular_movies']['results'] as $movie): ?>
                    <div class="flex-none w-[250px]">
                        <div class="relative group/item transition-transform duration-300 hover:scale-110 hover:z-10">
                            <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($movie['title']) ?>"
                                 class="rounded-md w-full">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover/item:opacity-50 transition-opacity rounded-md"></div>
                            <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                <h3 class="text-sm font-bold"><?= htmlspecialchars($movie['title']) ?></h3>
                                <div class="flex items-center space-x-2 mt-2">
                                    <button class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center hover:bg-opacity-80">
                                        <i class="fas fa-play text-sm"></i>
                                    </button>
                                    <button class="w-8 h-8 rounded-full border border-white flex items-center justify-center hover:bg-white hover:text-black">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Séries populaires -->
    <section class="slider-section mb-12">
        <h2 class="text-2xl font-bold mb-4 px-12">Séries du moment</h2>
        <div class="relative group">
            <div class="flex overflow-x-auto hide-scrollbar px-12 space-x-4">
                <?php foreach ($categories['popular_tv']['results'] as $show): ?>
                    <div class="flex-none w-[250px]">
                        <div class="relative group/item transition-transform duration-300 hover:scale-110 hover:z-10">
                            <img src="https://image.tmdb.org/t/p/w500<?= $show['poster_path'] ?>" 
                                 alt="<?= htmlspecialchars($show['name']) ?>"
                                 class="rounded-md w-full">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover/item:opacity-50 transition-opacity rounded-md"></div>
                            <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                <h3 class="text-sm font-bold"><?= htmlspecialchars($show['name']) ?></h3>
                                <div class="flex items-center space-x-2 mt-2">
                                    <button class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center hover:bg-opacity-80">
                                        <i class="fas fa-play text-sm"></i>
                                    </button>
                                    <button class="w-8 h-8 rounded-full border border-white flex items-center justify-center hover:bg-white hover:text-black">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
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