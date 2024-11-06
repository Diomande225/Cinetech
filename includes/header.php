<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'La Cinétech' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-black text-white">
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
                                    <p class="px-4 text-sm text-gray-300"><?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                                    <a href="/profile" class="block px-4 py-2 text-sm hover:bg-gray-800">Gérer le profil</a>
                                    <a href="/favorites" class="block px-4 py-2 text-sm hover:bg-gray-800">Ma liste</a>
                                    <a href="/settings" class="block px-4 py-2 text-sm hover:bg-gray-800">Paramètres</a>
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
    <main>