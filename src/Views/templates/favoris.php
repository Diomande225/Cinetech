<main class="pt-20 container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Mes Favoris</h1>

    <?php if (empty($favoris)): ?>
        <div class="text-center py-8">
            <p class="text-white text-xl mb-4">Vous n'avez pas encore ajouté de favoris.</p>
            <a href="/Cinetech" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                Découvrir des films et séries
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($favoris as $favori): ?>
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg relative group">
                    <!-- Image avec lien -->
                    <a href="/Cinetech/detail/<?= $favori['media_type'] ?>/<?= $favori['item_id'] ?>" class="block">
                        <img 
                            src="<?= $favori['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $favori['poster_path'] : "./public/img/placeholder.jpg" ?>" 
                            alt="<?= htmlspecialchars($favori['title'] ?? $favori['name']) ?>" 
                            class="w-full h-60 object-cover transition-transform duration-300 group-hover:scale-105"
                        >
                    </a>

                    <!-- Informations -->
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2 line-clamp-2">
                            <?= htmlspecialchars($favori['title'] ?? $favori['name']) ?>
                        </h3>
                        <p class="text-sm text-gray-400 mb-3">
                            <?= $favori['media_type'] === 'movie' ? 'Film' : 'Série' ?>
                        </p>
                        
                        <!-- Bouton Favori -->
                        <button 
                            type="button"
                            class="favori-button active w-full flex items-center justify-center space-x-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
                            data-item-id="<?= $favori['item_id'] ?>"
                            data-media-type="<?= $favori['media_type'] ?>"
                            onclick="handleFavoriClick.call(this, event)"
                            style="cursor: pointer; z-index: 10;"
                        >
                            <i class="fas fa-heart"></i>
                            <span>Retirer des favoris</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg">
            <span id="notification-message"></span>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/Cinetech/public/js/favoris.js"></script>
<script>
    // Vérifier que le JavaScript est chargé
    console.log('Template JavaScript loaded');
    
    // Attacher les événements aux boutons manuellement
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded in template');
        const buttons = document.querySelectorAll('.favori-button');
        console.log('Found buttons:', buttons.length);
        
        buttons.forEach(button => {
            console.log('Button data:', {
                itemId: button.dataset.itemId,
                mediaType: button.dataset.mediaType,
                isActive: button.classList.contains('active')
            });
        });
    });
</script>