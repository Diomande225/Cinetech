<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Définir le chemin de base
$basePath = '/Cinetech';

// Initialiser la langue
require_once __DIR__ . '/../../../../src/Lang/helpers.php';
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>/public/css/responsive.css">
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
<body class="bg-black text-white" 
    data-base-path="<?php echo $basePath; ?>"
    data-translation-search-in-progress="<?= __('search_in_progress') ?>"
    data-translation-no-results="<?= __('no_results') ?>"
    data-translation-error-occurred="<?= __('error_occurred') ?>"
    data-translation-year-unknown="<?= __('year_unknown') ?>"
    data-translation-min-chars="<?= __('min_chars') ?>"
>
    <header class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-b from-black to-transparent">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="<?php echo $basePath; ?>/home" class="flex items-center">
                    <span class="netflix-logo text-4xl font-bold text-red-600 hover:text-red-500 transition duration-300">CINETECH</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="<?php echo $basePath; ?>/movies" class="text-white hover:text-gray-300"><?= __('movies') ?></a>
                    <a href="<?php echo $basePath; ?>/tvseries" class="text-white hover:text-gray-300"><?= __('tvseries') ?></a>
                    <a href="<?php echo $basePath; ?>/favoris" class="text-white hover:text-gray-300"><?= __('favorites') ?></a>
                </div>

                <!-- Search Bar with Autocomplete -->
                <div class="relative flex-1 max-w-xs mx-4 search-container">
                    <input 
                        type="text" 
                        id="search-input"
                        placeholder="<?= __('search') ?>" 
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
                    <!-- Language Selector -->
                    <div class="relative inline-block text-left">
                        <div>
                            <button type="button" id="language-menu-button" class="inline-flex items-center justify-center w-full rounded-md px-2 py-1 bg-gray-800 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none">
                                <i class="fas fa-globe mr-1"></i>
                                <span class="hidden md:inline"><?= __('language') ?></span>
                                <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div id="language-menu" class="hidden absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <?php foreach(getAvailableLanguages() as $code => $name): ?>
                                <a href="<?= $basePath ?>/language/change/<?= $code ?>" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">
                                    <?= $name ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isset($_SESSION['username'])): ?>
                        <span class="text-white"><?= __('hello') ?>, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <a href="<?php echo $basePath; ?>/profile" class="text-white hover:text-gray-300"><?= __('profile') ?></a>
                        <a href="<?php echo $basePath; ?>/logout" class="text-red-600 hover:underline"><?= __('logout') ?></a>
                    <?php else: ?>
                        <a href="<?php echo $basePath; ?>/login" class="hidden md:block px-4 py-1 bg-red-600 text-white rounded-sm hover:bg-red-700 transition duration-300 text-sm font-medium"><?= __('login') ?></a>
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
                <div class="mb-4">
                    <input 
                        type="text" 
                        id="mobile-search-input"
                        placeholder="<?= __('search') ?>" 
                        class="w-full py-2 px-4 bg-opacity-50 bg-gray-700 text-white rounded-full border-none focus:outline-none focus:ring-2 focus:ring-white"
                    />
                </div>
                <a href="<?php echo $basePath; ?>/movies" class="block py-2 text-white hover:text-gray-300"><?= __('movies') ?></a>
                <a href="<?php echo $basePath; ?>/tvseries" class="block py-2 text-white hover:text-gray-300"><?= __('tvseries') ?></a>
                <a href="<?php echo $basePath; ?>/favoris" class="block py-2 text-white hover:text-gray-300"><?= __('favorites') ?></a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="<?php echo $basePath; ?>/profile" class="block py-2 text-white hover:text-gray-300"><?= __('profile') ?></a>
                    <a href="<?php echo $basePath; ?>/logout" class="block py-2 text-red-600 hover:underline"><?= __('logout') ?></a>
                <?php else: ?>
                    <a href="<?php echo $basePath; ?>/login" class="block py-2 text-white hover:text-gray-300"><?= __('login') ?></a>
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
        
        // Script pour le sélecteur de langue
        const languageMenuButton = document.getElementById('language-menu-button');
        const languageMenu = document.getElementById('language-menu');
        
        if (languageMenuButton && languageMenu) {
            languageMenuButton.addEventListener('click', () => {
                languageMenu.classList.toggle('hidden');
            });
            
            // Fermer le menu de langue lors d'un clic à l'extérieur
            document.addEventListener('click', (event) => {
                if (!languageMenuButton.contains(event.target) && !languageMenu.contains(event.target)) {
                    languageMenu.classList.add('hidden');
                }
            });
        }
        
        // Script pour synchroniser la barre de recherche mobile avec celle du bureau
        document.addEventListener('DOMContentLoaded', function() {
            const mobileSearchInput = document.getElementById('mobile-search-input');
            const desktopSearchInput = document.getElementById('search-input');
            
            if (mobileSearchInput && desktopSearchInput) {
                // Synchroniser les entrées
                mobileSearchInput.addEventListener('input', function() {
                    desktopSearchInput.value = this.value;
                    // Déclencher l'événement input sur la recherche de bureau pour activer la recherche
                    desktopSearchInput.dispatchEvent(new Event('input'));
                });
                
                // Afficher les résultats de recherche mobile
                const searchResults = document.getElementById('search-results');
                if (searchResults) {
                    const mobileSearchResults = searchResults.cloneNode(true);
                    mobileSearchResults.id = 'mobile-search-results';
                    mobileSearchResults.classList.add('w-full', 'mt-2');
                    mobileSearchInput.parentNode.appendChild(mobileSearchResults);
                    
                    // Observer les changements dans les résultats de recherche du bureau
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'childList' || mutation.type === 'attributes') {
                                // Copier le contenu
                                mobileSearchResults.innerHTML = searchResults.innerHTML;
                                
                                // Afficher si le bureau est visible et a du contenu
                                if (!searchResults.classList.contains('hidden') && searchResults.children.length > 0) {
                                    mobileSearchResults.classList.remove('hidden');
                                } else {
                                    mobileSearchResults.classList.add('hidden');
                                }
                            }
                        });
                    });
                    
                    observer.observe(searchResults, { 
                        attributes: true, 
                        childList: true, 
                        subtree: true 
                    });
                }
            }
            
            // Script pour l'effet de scroll sur la navigation
            const header = document.querySelector('header.fixed');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                });
            }
        });
    </script>