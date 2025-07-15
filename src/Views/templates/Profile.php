<main class="container mx-auto px-4 pt-20 pb-20">
    <h1 class="text-3xl font-semibold text-center mb-6">Profil de <?= htmlspecialchars($user['username']) ?></h1>
    <div class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <p class="text-white"><strong>Nom d'utilisateur:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p class="text-white"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <div class="mt-4 text-center">
            <a href="/logout" class="text-red-600 hover:underline">Déconnexion</a>
        </div>
    </div>
</main>