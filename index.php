<?php
session_start();

class HomeController {
    private $api_key;

    public function __construct() {
        $this->api_key = 'c21ac6ce8a090027847698c1f58d5a71';
    }

    private function fetchTMDBData($endpoint) {
        try {
            $arrContextOptions = [
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
                'http' => [
                    'timeout' => 30
                ]
            ];

            $url = "https://api.themoviedb.org/3/{$endpoint}?api_key={$this->api_key}&language=fr-FR";
            $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
            
            if ($response === false) {
                return ['results' => []];
            }
            
            return json_decode($response, true);
        } catch (Exception $e) {
            return ['results' => []];
        }
    }

    public function getPopularContent() {
        // Récupérer un film populaire aléatoire pour le banner
        $popularMovies = $this->fetchTMDBData('trending/movie/week');
        $featuredMovie = $popularMovies['results'][array_rand($popularMovies['results'])];
        
        // Récupérer les détails complets du film mis en avant
        $featuredDetails = $this->fetchTMDBData("movie/{$featuredMovie['id']}");
        
        return [
            'featured' => $featuredDetails,
            'trending' => $this->fetchTMDBData('trending/all/day')['results'],
            'movies' => $this->fetchTMDBData('movie/popular')['results'],
            'shows' => $this->fetchTMDBData('tv/popular')['results']
        ];
    }
}

$controller = new HomeController();
$content = $controller->getPopularContent();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Cinétech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-black text-white">
    <!-- Header Netflix-style -->
    <header class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <a href="/" class="text-red-600 text-3xl font-bold">CINÉTECH</a>
                    <div class="hidden md:flex space-x-6">
                        <a href="/" class="text-sm hover:text-gray-300">Accueil</a>
                        <a href="/movies" class="text-sm hover:text-gray-300">Films</a>
                        <a href="/tv-shows" class="text-sm hover:text-gray-300">Séries</a>
                        <a href="/my-list" class="text-sm hover:text-gray-300">Ma Liste</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Titres, personnes, genres" 
                               class="bg-black bg-opacity-50 border border-gray-600 text-sm rounded-full px-4 py-2 w-48 focus:outline-none focus:border-white transition-all">
                    </div>
                    
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="relative group">
                            <div class="flex items-center space-x-2 cursor-pointer">
                                <img src="/assets/images/avatar.png" alt="Profile" class="w-8 h-8 rounded">
                                <i class="fas fa-caret-down"></i>
                            </div>
                            <div class="absolute right-0 mt-2 w-48 bg-black bg-opacity-90 border border-gray-700 rounded shadow-lg hidden group-hover:block">
                                <div class="py-2">
                                    <a href="/profile" class="block px-4 py-2 text-sm hover:bg-gray-800">Gérer le profil</a>
                                    <a href="/favorites" class="block px-4 py-2 text-sm hover:bg-gray-800">Ma liste</a>
                                    <hr class="border-gray-700 my-2">
                                    <a href="/logout" class="block px-4 py-2 text-sm hover:bg-gray-800">Se déconnecter</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">S'identifier</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Banner -->
    <div class="relative h-screen">
        <div class="absolute inset-0">
            <img src="https://image.tmdb.org/t/p/original<?= $content['featured']['backdrop_path'] ?>" 
                 alt="<?= htmlspecialchars($content['featured']['title']) ?>"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
        </div>
        
        <div class="relative z-10 h-full flex items-center">
            <div class="container mx-auto px-12 pt-32">
                <h1 class="text-7xl font-bold mb-4 max-w-2xl">
                    <?= htmlspecialchars($content['featured']['title']) ?>
                </h1>
                <p class="text-xl max-w-2xl mb-8 text-gray-200">
                    <?= htmlspecialchars($content['featured']['overview']) ?>
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
    <div class="relative z-10 -mt-32 pb-12">
        <!-- Tendances -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 px-12">Tendances actuelles</h2>
            <div class="relative group">
                <div class="flex overflow-x-auto hide-scrollbar px-12 space-x-4">
                    <?php foreach (array_slice($content['trending'], 0, 10) as $item): ?>
                        <?php 
                            // Déterminer si c'est un film ou une série
                            $contentType = isset($item['title']) ? 'movie' : 'tv';
                            $contentUrl = $contentType === 'movie' ? "/movie/{$item['id']}" : "/tv/{$item['id']}";
                        ?>
                        <a href="<?= $contentUrl ?>" class="flex-none w-[250px] group/card">
                            <div class="relative group/item transition-transform duration-300 hover:scale-110 hover:z-10">
                                <img src="https://image.tmdb.org/t/p/w500<?= $item['poster_path'] ?>" 
                                     alt="<?= htmlspecialchars($item['title'] ?? $item['name']) ?>"
                                     class="rounded-md w-full">
                                <div class="absolute inset-0 bg-black opacity-0 group-hover/item:opacity-50 transition-opacity rounded-md"></div>
                                <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                    <h3 class="text-sm font-bold"><?= htmlspecialchars($item['title'] ?? $item['name']) ?></h3>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="event.preventDefault(); playTrailer(<?= $item['id'] ?>)" 
                                                    class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center hover:bg-opacity-80">
                                                <i class="fas fa-play text-sm"></i>
                                            </button>
                                            <button onclick="event.preventDefault(); toggleFavorite(<?= $item['id'] ?>)" 
                                                    class="w-8 h-8 rounded-full border border-white flex items-center justify-center hover:bg-white hover:text-black">
                                                <i class="fas fa-plus text-sm"></i>
                                            </button>
                                        </div>
                                        <div class="text-sm">
                                            <i class="fas fa-star text-yellow-500"></i>
                                            <?= number_format($item['vote_average'], 1) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Films populaires -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 px-12">Films populaires</h2>
            <div class="relative group">
                <div class="flex overflow-x-auto hide-scrollbar px-12 space-x-4">
                    <?php foreach ($content['movies'] as $movie): ?>
                        <a href="/movie/<?= $movie['id'] ?>" class="flex-none w-[250px] group/card">
                            <div class="relative group/item transition-transform duration-300 hover:scale-110 hover:z-10">
                                <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
                                     alt="<?= htmlspecialchars($movie['title']) ?>"
                                     class="rounded-md w-full">
                                <div class="absolute inset-0 bg-black opacity-0 group-hover/item:opacity-50 transition-opacity rounded-md"></div>
                                <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                    <h3 class="text-sm font-bold"><?= htmlspecialchars($movie['title']) ?></h3>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="event.preventDefault(); playTrailer(<?= $movie['id'] ?>)" 
                                                    class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center hover:bg-opacity-80">
                                                <i class="fas fa-play text-sm"></i>
                                            </button>
                                            <button onclick="event.preventDefault(); toggleFavorite(<?= $movie['id'] ?>)" 
                                                    class="w-8 h-8 rounded-full border border-white flex items-center justify-center hover:bg-white hover:text-black">
                                                <i class="fas fa-plus text-sm"></i>
                                            </button>
                                        </div>
                                        <div class="text-sm">
                                            <i class="fas fa-star text-yellow-500"></i>
                                            <?= number_format($movie['vote_average'], 1) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
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

    <script>
    // Effet de scroll pour le navbar
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 0) {
            navbar.classList.add('bg-black');
        } else {
            navbar.classList.remove('bg-black');
        }
    });
    </script>
</body>
</html> 