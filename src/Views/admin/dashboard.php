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
        
        <!-- Onglets de navigation -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2">
                    <a href="?tab=users" class="inline-block p-4 <?= $viewData['activeTab'] == 'users' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-gray-600 hover:border-gray-300' ?>">
                        <i class="fas fa-users mr-2"></i> Gestion des utilisateurs
                    </a>
                </li>
                <li class="mr-2">
                    <a href="?tab=comments" class="inline-block p-4 <?= $viewData['activeTab'] == 'comments' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-gray-600 hover:border-gray-300' ?>">
                        <i class="fas fa-comments mr-2"></i> Gestion des commentaires
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Section des utilisateurs -->
        <div id="users-section" class="<?= $viewData['activeTab'] == 'users' ? 'block' : 'hidden' ?>">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-xl font-semibold mb-4">Gestion des utilisateurs</h2>
                
                <?php if (empty($viewData['users'])): ?>
                    <div class="bg-gray-100 p-4 rounded text-center">
                        <p>Aucun utilisateur à afficher.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Nom d'utilisateur</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Statut</th>
                                    <th class="px-4 py-3 text-left">Rôle</th>
                                    <th class="px-4 py-3 text-left">Date d'inscription</th>
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($viewData['users'] as $user): ?>
                                <tr class="hover:bg-gray-50" data-user-id="<?= $user['id'] ?>">
                                    <td class="px-4 py-3"><?= $user['id'] ?></td>
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($user['username']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="px-4 py-3">
                                        <?php if ($user['is_active'] ?? true): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Actif</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if ($user['is_admin']): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Admin</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Utilisateur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm"><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            <?php if ($_SESSION['user_id'] != $user['id']): ?>
                                                <?php if ($user['is_active'] ?? true): ?>
                                                    <button class="deactivate-user text-yellow-600 hover:text-yellow-900" title="Désactiver">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="activate-user text-green-600 hover:text-green-900" title="Activer">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button class="delete-user text-red-600 hover:text-red-900" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-sm">Vous-même</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Section des commentaires -->
        <div id="comments-section" class="<?= $viewData['activeTab'] == 'comments' ? 'block' : 'hidden' ?>">
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
                                    <th class="px-4 py-3 text-left">ID</th>
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
                                <tr class="hover:bg-gray-50" data-comment-id="<?= $comment['id'] ?>">
                                    <td class="px-4 py-3"><?= $comment['id'] ?></td>
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($comment['username']) ?></td>
                                    <td class="px-4 py-3 max-w-xs truncate"><?= htmlspecialchars($comment['content']) ?></td>
                                    <td class="px-4 py-3"><?= $comment['content_type'] ?></td>
                                    <td class="px-4 py-3"><?= $comment['item_id'] ?></td>
                                    <td class="px-4 py-3 text-sm"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></td>
                                    <td class="px-4 py-3">
                                        <button class="delete-comment text-red-600 hover:text-red-900" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?= date('Y') ?> Cinetech - Administration</p>
        </div>
    </footer>

    <!-- JavaScript pour les interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des commentaires
            const deleteCommentButtons = document.querySelectorAll('.delete-comment');
            deleteCommentButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const commentRow = this.closest('tr');
                    const commentId = commentRow.dataset.commentId;
                    
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                        fetch('<?= $viewData['basePath'] ?>/admin/comments/delete/' + commentId, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                commentRow.remove();
                                showNotification('Commentaire supprimé avec succès', 'success');
                            } else {
                                showNotification(data.message || 'Erreur lors de la suppression', 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Erreur de communication avec le serveur', 'error');
                            console.error('Error:', error);
                        });
                    }
                });
            });
            
            // Gestion des utilisateurs
            const activateUserButtons = document.querySelectorAll('.activate-user');
            activateUserButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userRow = this.closest('tr');
                    const userId = userRow.dataset.userId;
                    
                    if (confirm('Êtes-vous sûr de vouloir activer cet utilisateur ?')) {
                        fetch('<?= $viewData['basePath'] ?>/admin/users/activate/' + userId, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Utilisateur activé avec succès', 'success');
                                setTimeout(() => { window.location.reload(); }, 1500);
                            } else {
                                showNotification(data.message || 'Erreur lors de l\'activation', 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Erreur de communication avec le serveur', 'error');
                            console.error('Error:', error);
                        });
                    }
                });
            });
            
            const deactivateUserButtons = document.querySelectorAll('.deactivate-user');
            deactivateUserButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userRow = this.closest('tr');
                    const userId = userRow.dataset.userId;
                    
                    if (confirm('Êtes-vous sûr de vouloir désactiver cet utilisateur ?')) {
                        fetch('<?= $viewData['basePath'] ?>/admin/users/deactivate/' + userId, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Utilisateur désactivé avec succès', 'success');
                                setTimeout(() => { window.location.reload(); }, 1500);
                            } else {
                                showNotification(data.message || 'Erreur lors de la désactivation', 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Erreur de communication avec le serveur', 'error');
                            console.error('Error:', error);
                        });
                    }
                });
            });
            
            const deleteUserButtons = document.querySelectorAll('.delete-user');
            deleteUserButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userRow = this.closest('tr');
                    const userId = userRow.dataset.userId;
                    
                    if (confirm('ATTENTION: Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ? Cette action est irréversible et supprimera également tous ses commentaires et favoris.')) {
                        fetch('<?= $viewData['basePath'] ?>/admin/users/delete/' + userId, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                userRow.remove();
                                showNotification('Utilisateur supprimé avec succès', 'success');
                            } else {
                                showNotification(data.message || 'Erreur lors de la suppression', 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Erreur de communication avec le serveur', 'error');
                            console.error('Error:', error);
                        });
                    }
                });
            });
            
            // Fonction pour afficher des notifications
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                } text-white max-w-xs`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>
</html> 