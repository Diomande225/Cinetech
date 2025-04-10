<main class="container mx-auto px-4 pt-20 pb-20">
    <h1 class="text-3xl font-semibold text-center mb-6">Inscription</h1>
    <form method="POST" action="/Cinetech/register" class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium">Nom</label>
            <input type="text" id="name" name="name" class="w-full p-2 bg-gray-700 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" class="w-full p-2 bg-gray-700 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium">Mot de passe</label>
            <input type="password" id="password" name="password" class="w-full p-2 bg-gray-700 rounded-lg" required>
        </div>
        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg">S'inscrire</button>
        <div class="mt-4 text-center">
            <p class="text-sm">
                Déjà inscrit ? 
                <a href="/Cinetech/login" class="text-red-600 hover:underline">Se connecter</a>
            </p>
        </div>
    </form>
</main>