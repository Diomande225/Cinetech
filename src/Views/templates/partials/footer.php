<!-- footer.php -->
<footer class="bg-black text-white py-8 mt-20">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- À propos -->
        <div>
            <h2 class="text-lg font-semibold mb-4">À propos de Cinetech</h2>
            <p class="text-sm">
                Cinetech est une bibliothèque interactive dédiée aux passionnés de films et séries. 
                Explorez les dernières tendances, consultez les détails de vos contenus préférés et gérez vos favoris en un seul endroit.
            </p>
        </div>

        <!-- Liens utiles -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Liens utiles</h2>
            <ul>
                <li><a href="<?php echo $basePath; ?>/movies" class="text-red-600 hover:underline">Films populaires</a></li>
                <li><a href="<?php echo $basePath; ?>/tvseries" class="text-red-600 hover:underline">Séries populaires</a></li>
                <li><a href="<?php echo $basePath; ?>/favoris" class="text-red-600 hover:underline">Mes Favoris</a></li>
                <li><a href="<?php echo $basePath; ?>/login" class="text-red-600 hover:underline">Connexion</a></li>
                <li><a href="<?php echo $basePath; ?>/register" class="text-red-600 hover:underline">Inscription</a></li>
            </ul>
        </div>

        <!-- Contact et Réseaux sociaux -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Nous contacter</h2>
            <p class="text-sm mb-2">Une question ou une suggestion ? Écrivez-nous à :</p>
            <p class="text-sm mb-4">
                <a href="mailto:contact@cinetech.com" class="text-red-600 hover:underline">contact@cinetech.com</a>
            </p>
            <h3 class="text-lg font-semibold mb-2">Suivez-nous :</h3>
            <div class="flex space-x-4">
                <a href="#" class="text-white hover:text-red-600"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="#" class="text-white hover:text-red-600"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#" class="text-white hover:text-red-600"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
    </div>
    <div class="mt-8 text-center border-t border-gray-700 pt-4">
        <p class="text-sm">&copy; 2024 Cinetech - Tous droits réservés.</p>
        <p class="text-sm">Mentions légales | Politique de confidentialité</p>
    </div>
</footer>

<!-- Bottom Navigation Bar - Only visible on mobile -->
<div class="fixed md:hidden bottom-0 left-0 right-0 bg-black shadow-lg w-full z-40 bottom-nav">
    <nav class="max-w-screen-lg mx-auto">
        <div class="flex justify-between items-center px-4 py-3">
            <a href="<?php echo $basePath; ?>/movies" class="flex flex-col items-center text-sm text-white hover:text-red-500 transition duration-300 bottom-nav-icon">
                <i class="fas fa-film text-xl mb-1"></i>
                <span><?= __('movies') ?></span>
            </a>
            <a href="<?php echo $basePath; ?>/tvseries" class="flex flex-col items-center text-sm text-white hover:text-red-500 transition duration-300 bottom-nav-icon">
                <i class="fas fa-tv text-xl mb-1"></i>
                <span><?= __('tvseries') ?></span>
            </a>
            <a href="<?php echo $basePath; ?>/favoris" class="flex flex-col items-center text-sm text-white hover:text-red-500 transition duration-300 bottom-nav-icon">
                <i class="fas fa-heart text-xl mb-1"></i>
                <span><?= __('favorites') ?></span>
            </a>
        </div>
    </nav>
</div>

<!-- Extra space to prevent content from being hidden behind the bottom navbar -->
<div class="pb-16"></div>

<!-- Inclusion du fichier favoris.js -->
<script src="<?php echo $basePath; ?>/js/favoris.js"></script>

</body>
</html>
