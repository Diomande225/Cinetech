<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Définir le chemin de base
$basePath = '/Cinetech';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo $basePath; ?>/public/js/search.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:wght@400;600&display=swap');
        body {
            font-family: 'Open Sans', sans-serif;
        }
        .netflix-logo {
            font-family: 'Bebas Neue', cursive;
        }
    </style>
</head>
<body class="bg-black text-white" data-base-path="<?php echo $basePath; ?>">
    <header class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-b from-black to-transparent">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="<?php echo $basePath; ?>/home" class="flex items-center">
                    <span class="netflix-logo text-4xl font-bold text-red-600 hover:text-red-500 transition duration-300">CINETECH</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="<?php echo $basePath; ?>/movies" class="text-white hover:text-gray-300">Films</a>
                    <a href="<?php echo $basePath; ?>/tvseries" class="text-white hover:text-gray-300">Séries</a>
                    <a href="<?php echo $basePath; ?>/favoris" class="text-white hover:text-gray-300">Favoris</a>
                </div>

                <!-- Search Bar with Autocomplete -->
                <div class="relative flex-1 max-w-xs mx-4">
                    <input 
                        type="text" 
                        id="search-input"
                        placeholder="Rechercher..." 
                        class="w-full py-2 px-4 bg-opacity-50 bg-gray-700 text-white rounded-full border-none focus:outline-none focus:ring-2 focus:ring-white"
                    />
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <div 
                        id="search-results" 
                        class="absolute z-20 w-full bg-black bg-opacity-90 mt-1 rounded-sm shadow-lg max-h-96 overflow-y-auto hidden"
                    >
                        <!-- Les résultats d'autocomplétion seront ajoutés ici dynamiquement -->
                    </div>
                </div>

                <!-- User Account Links -->
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['username'])): ?>
                        <span class="text-white">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <a href="<?php echo $basePath; ?>/profile" class="text-white hover:text-gray-300">Profil</a>
                        <a href="<?php echo $basePath; ?>/logout" class="text-red-600 hover:underline">Déconnexion</a>
                    <?php else: ?>
                        <a href="<?php echo $basePath; ?>/login" class="hidden md:block px-4 py-1 bg-red-600 text-white rounded-sm hover:bg-red-700 transition duration-300 text-sm font-medium">S'identifier</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4">
                <a href="<?php echo $basePath; ?>/movies" class="block py-2 text-white hover:text-gray-300">Films</a>
                <a href="<?php echo $basePath; ?>/tvseries" class="block py-2 text-white hover:text-gray-300">Séries</a>
                <a href="<?php echo $basePath; ?>/favoris" class="block py-2 text-white hover:text-gray-300">Favoris</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="<?php echo $basePath; ?>/profile" class="block py-2 text-white hover:text-gray-300">Profil</a>
                    <a href="<?php echo $basePath; ?>/logout" class="block py-2 text-red-600 hover:underline">Déconnexion</a>
                <?php else: ?>
                    <a href="<?php echo $basePath; ?>/login" class="block py-2 text-white hover:text-gray-300">S'identifier</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <script>
        // Script pour le menu mobile
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>