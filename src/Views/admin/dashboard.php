<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewData['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= $viewData['basePath'] ?>/public/css/responsive.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-gray-900 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <a href="<?= $viewData['basePath'] ?>/admin" class="text-xl font-bold">Cinetech Admin</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="<?= $viewData['basePath'] ?>" class="hover:text-gray-300"><i class="fas fa-home mr-1"></i> Retour au site</a>
                <a href="<?= $viewData['basePath'] ?>/logout" class="hover:text-gray-300"><i class="fas fa-sign-out-alt mr-1"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <main class="flex-1 container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Tableau de bord d'administration</h1>
        
        <!-- Section des commentaires -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Gestion des commentaires</h2>
            
            <?php if (empty($viewData['comments'])): ?>
                <div class="bg-gray-100 p-4 rounded text-center">
                    <p>Aucun commentaire à afficher.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">Utilisateur</th>
                                <th class="px-4 py-3 text-left">Commentaire</th>
                                <th class="px-4 py-3 text-left">Type</th>
                                <th class="px-4 py-3 text-left">ID Média</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($viewData['comments'] as $comment): ?>
                                <tr class="hover:bg-gray-50" id="comment-<?= $comment['id'] ?>">
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($comment['username']) ?></td>
                                    <td class="px-4 py-3 max-w-md truncate">
                                        <span title="<?= htmlspecialchars($comment['content']) ?>">
                                            <?= htmlspecialchars(substr($comment['content'], 0, 100)) . (strlen($comment['content']) > 100 ? '...' : '') ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($comment['content_type']) ?></td>
                                    <td class="px-4 py-3">
                                        <a href="<?= $viewData['basePath'] ?>/detail/<?= $comment['item_type'] ?>/<?= $comment['item_id'] ?>" 
                                           class="text-blue-600 hover:underline" target="_blank">
                                            <?= $comment['item_id'] ?>
                                        </a>
                                    </td>
                                    <td class="px-4 py-3"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></td>
                                    <td class="px-4 py-3">
                                        <button onclick="deleteComment(<?= $comment['id'] ?>)" 
                                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 flex items-center">
                                            <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <footer class="bg-gray-900 text-white text-center p-4">
        <p>&copy; <?= date('Y') ?> Cinetech - Panneau d'administration</p>
    </footer>
    
    <script>
        function deleteComment(commentId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.')) {
                return;
            }
            
            fetch(`<?= $viewData['basePath'] ?>/admin/comments/delete/${commentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer la ligne du tableau sans recharger la page
                    document.getElementById(`comment-${commentId}`).remove();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la suppression du commentaire.');
            });
        }
    </script>
</body>
</html> 