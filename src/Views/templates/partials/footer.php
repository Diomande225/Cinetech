<!-- footer.php -->
<footer class="bg-gray-900 text-white py-8">
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
                <li><a href="/Cinetech/movies" class="text-red-600 hover:underline">Films populaires</a></li>
                <li><a href="/Cinetech/tvseries" class="text-red-600 hover:underline">Séries populaires</a></li>
                <li><a href="/Cinetech/favoris" class="text-red-600 hover:underline">Mes Favoris</a></li>
                <li><a href="/Cinetech/login" class="text-red-600 hover:underline">Connexion</a></li>
                <li><a href="/Cinetech/register" class="text-red-600 hover:underline">Inscription</a></li>
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
<!-- Inclusion du fichier favoris.js -->
<script src="/public/js/favoris.js"></script>

</body>
</html>
