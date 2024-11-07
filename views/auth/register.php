<?php 
$pageTitle = "Inscription - La Cinétech";
require_once 'includes/header.php'; 
?>

<div class="container mx-auto px-4 pt-32">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Inscription</h1>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <form action="/Cinetech/register" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block mb-1">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required
                       class="w-full bg-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full bg-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label for="password" class="block mb-1">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="w-full bg-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <label for="confirm_password" class="block mb-1">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                       class="w-full bg-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <button type="submit" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                S'inscrire
            </button>
        </form>

        <p class="mt-4 text-center">
            Déjà un compte ? 
            <a href="/Cinetech/login" class="text-red-500 hover:text-red-400">Connectez-vous</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 