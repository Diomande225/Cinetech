    </main>
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-between">
                <!-- Section Informations -->
                <div class="w-full md:w-1/3 mb-6 md:mb-0">
                    <h4 class="text-lg font-bold mb-4">À propos de Cinétech</h4>
                    <p class="text-sm">
                        Cinétech est votre source ultime pour découvrir des films et séries. Explorez notre vaste bibliothèque et trouvez vos favoris.
                    </p>
                </div>

                <!-- Section Liens Utiles -->
                <div class="w-full md:w-1/3 mb-6 md:mb-0">
                    <h4 class="text-lg font-bold mb-4">Liens Utiles</h4>
                    <ul class="text-sm">
                        <li><a href="/Cinetech/movies" class="hover:text-red-600">Films</a></li>
                        <li><a href="/Cinetech/tv-shows" class="hover:text-red-600">Séries</a></li>
                        <li><a href="/Cinetech/favorites" class="hover:text-red-600">Mes Favoris</a></li>
                        <li><a href="/Cinetech/contact" class="hover:text-red-600">Contact</a></li>
                    </ul>
                </div>

                <!-- Section Réseaux Sociaux -->
                <div class="w-full md:w-1/3">
                    <h4 class="text-lg font-bold mb-4">Suivez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-red-600"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-red-600"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-red-600"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-red-600"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Section Copyright -->
            <div class="mt-8 text-center text-sm">
                &copy; <?= date('Y'); ?> Cinétech. Tous droits réservés.
            </div>
        </div>
    </footer>
    <script src="/assets/js/main.js"></script>
</body>
</html> 