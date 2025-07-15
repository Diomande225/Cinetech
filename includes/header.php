<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'La Cinétech' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/Cinetech/public/css/favorites.css" rel="stylesheet">
    <script>
        const isAuthenticated = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;
    </script>
    <script src="/Cinetech/public/js/favorites.js" defer></script>
    <script>
        console.log('Script favorites.js chargé');
    </script>
    <script>
    async function toggleFavorite(event, mediaId, mediaType) {
        event.preventDefault();
        event.stopPropagation();
        
        try {
            const response = await fetch('/Cinetech/api/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    media_id: mediaId, 
                    media_type: mediaType 
                })
            });

            const data = await response.json();
            
            if (data.success) {
                const icon = event.currentTarget.querySelector('i.fa-heart');
                if (data.isFavorite) {
                    icon.classList.remove('text-white');
                    icon.classList.add('text-red-600');
                } else {
                    icon.classList.remove('text-red-600');
                    icon.classList.add('text-white');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    }
    </script>
   
</head>
<body class="bg-black text-white min-h-screen">
    <header>
        <nav id="navbar" class="fixed w-full z-50 bg-black bg-opacity-75">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <!-- Logo -->
                <a href="/Cinetech" class="text-red-600 text-2xl font-bold">CINÉTECH</a>

                <!-- Navigation principale -->
                <div class="hidden md:flex space-x-6">
                    <a href="/Cinetech/movies" class="text-white hover:text-gray-300">Films</a>
                    <a href="/Cinetech/tv-shows" class="text-white hover:text-gray-300">Séries</a>
                    <a href="/Cinetech/favorites" class="text-white hover:text-gray-300">
                        <i class="fas fa-heart"></i> Mes Favoris
                    </a>
                </div>

                <!-- Recherche et Authentification -->
                <div class="flex items-center space-x-4">
                    <form action="/Cinetech/search" method="GET" class="relative hidden md:block">
                        <input type="text" 
                               name="q" 
                               placeholder="Titres, personnes, genres" 
                               class="bg-gray-800 text-white px-4 py-2 rounded-full w-64 focus:outline-none focus:ring-2 focus:ring-red-600">
                    </form>
                    
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 hover:text-red-600 transition">
                                <span><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="absolute right-0 w-48 pt-2 hidden group-hover:block">
                                <div class="bg-gray-900 rounded-lg shadow-lg py-2">
                                    <a href="/Cinetech/profile" class="block px-4 py-2 hover:bg-gray-800 transition">
                                        <i class="fas fa-user mr-2"></i>Profil
                                    </a>
                                    <a href="/Cinetech/favorites" class="block px-4 py-2 hover:bg-gray-800 transition">
                                        <i class="fas fa-heart mr-2"></i>Favoris
                                    </a>
                                    <a href="/Cinetech/settings" class="block px-4 py-2 hover:bg-gray-800 transition">
                                        <i class="fas fa-cog mr-2"></i>Paramètres
                                    </a>
                                    <hr class="my-2 border-gray-700">
                                    <a href="/Cinetech/logout" class="block px-4 py-2 hover:bg-gray-800 transition text-red-500">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/Cinetech/auth/login" class="hover:text-red-600 transition">S'identifier</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <script src="/Cinetech/public/js/main.js" defer></script>
    <main>