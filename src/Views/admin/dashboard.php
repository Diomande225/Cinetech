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
                
                <div class="mb-4">
                    <button id="add-user-button" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter un utilisateur
                    </button>
                </div>
                
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
                                                <button class="edit-user text-blue-600 hover:text-blue-900" title="Modifier" data-user-id="<?= $user['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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

    <!-- Modal d'édition d'utilisateur -->
    <div id="edit-user-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <!-- En-tête -->
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold text-gray-800">Modifier l'utilisateur</p>
                    <div class="modal-close cursor-pointer z-50">
                        <svg class="fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9l4.47-4.47z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Formulaire -->
                <form id="edit-user-form">
                    <input type="hidden" id="edit-user-id">
                    
                    <div class="mb-4">
                        <label for="edit-username" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur</label>
                        <input type="text" id="edit-username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-red-500 text-xs italic hidden" id="username-error"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" id="edit-email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-red-500 text-xs italic hidden" id="email-error"></p>
                    </div>
                    
                    <div class="flex justify-end pt-2">
                        <button type="button" class="modal-close px-4 bg-gray-500 text-white rounded-lg py-2 mr-2">Annuler</button>
                        <button type="submit" class="px-4 bg-blue-500 text-white rounded-lg py-2">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal d'ajout d'utilisateur -->
    <div id="add-user-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <!-- En-tête -->
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold text-gray-800">Ajouter un utilisateur</p>
                    <div class="modal-close cursor-pointer z-50">
                        <svg class="fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9l4.47-4.47z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Formulaire -->
                <form id="add-user-form">
                    <div class="mb-4">
                        <label for="add-username" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur</label>
                        <input type="text" id="add-username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-red-500 text-xs italic hidden" id="add-username-error"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="add-email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" id="add-email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-red-500 text-xs italic hidden" id="add-email-error"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="add-password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                        <input type="password" id="add-password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-red-500 text-xs italic hidden" id="add-password-error"></p>
                    </div>
                    
                    <div class="flex justify-end pt-2">
                        <button type="button" class="modal-close px-4 bg-gray-500 text-white rounded-lg py-2 mr-2">Annuler</button>
                        <button type="submit" class="px-4 bg-green-500 text-white rounded-lg py-2">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            
            // Gestion de l'édition des utilisateurs
            const editUserButtons = document.querySelectorAll('.edit-user');
            const editUserModal = document.getElementById('edit-user-modal');
            const editUserForm = document.getElementById('edit-user-form');
            const editUserId = document.getElementById('edit-user-id');
            const editUsername = document.getElementById('edit-username');
            const editEmail = document.getElementById('edit-email');
            const usernameError = document.getElementById('username-error');
            const emailError = document.getElementById('email-error');
            const modalCloseButtons = document.querySelectorAll('.modal-close');
            
            // Ouvrir le modal d'édition
            editUserButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    
                    // Réinitialiser les erreurs
                    usernameError.classList.add('hidden');
                    emailError.classList.add('hidden');
                    
                    // Récupérer les données de l'utilisateur
                    fetch('<?= $viewData['basePath'] ?>/admin/users/details/' + userId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remplir le formulaire
                                editUserId.value = data.user.id;
                                editUsername.value = data.user.username;
                                editEmail.value = data.user.email;
                                
                                // Afficher le modal
                                editUserModal.classList.remove('hidden');
                            } else {
                                showNotification(data.message || 'Erreur lors de la récupération des données', 'error');
                            }
                        })
                        .catch(error => {
                            showNotification('Erreur de communication avec le serveur', 'error');
                            console.error('Error:', error);
                        });
                });
            });
            
            // Fermer le modal
            modalCloseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    editUserModal.classList.add('hidden');
                });
            });
            
            // Gestion du clic en dehors du modal pour le fermer
            editUserModal.addEventListener('click', function(event) {
                if (event.target === editUserModal) {
                    editUserModal.classList.add('hidden');
                }
            });
            
            // Soumission du formulaire
            editUserForm.addEventListener('submit', function(event) {
                event.preventDefault();
                
                // Réinitialiser les erreurs
                usernameError.classList.add('hidden');
                emailError.classList.add('hidden');
                
                // Récupérer les données du formulaire
                const userId = editUserId.value;
                const username = editUsername.value.trim();
                const email = editEmail.value.trim();
                
                // Envoyer les données
                fetch('<?= $viewData['basePath'] ?>/admin/users/update/' + userId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: username,
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fermer le modal
                        editUserModal.classList.add('hidden');
                        
                        // Afficher la notification de succès
                        showNotification('Utilisateur mis à jour avec succès', 'success');
                        
                        // Mettre à jour les données dans le tableau
                        const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                        if (userRow) {
                            userRow.querySelector('td:nth-child(2)').textContent = username;
                            userRow.querySelector('td:nth-child(3)').textContent = email;
                        }
                    } else {
                        // Afficher les erreurs
                        if (data.errors) {
                            data.errors.forEach(error => {
                                if (error.includes('nom d\'utilisateur')) {
                                    usernameError.textContent = error;
                                    usernameError.classList.remove('hidden');
                                } else if (error.includes('email')) {
                                    emailError.textContent = error;
                                    emailError.classList.remove('hidden');
                                }
                            });
                        } else {
                            showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
                        }
                    }
                })
                .catch(error => {
                    showNotification('Erreur de communication avec le serveur', 'error');
                    console.error('Error:', error);
                });
            });
            
            // Gestion de l'ajout d'utilisateur
            const addUserButton = document.getElementById('add-user-button');
            const addUserModal = document.getElementById('add-user-modal');
            const addUserForm = document.getElementById('add-user-form');
            const addUsername = document.getElementById('add-username');
            const addEmail = document.getElementById('add-email');
            const addPassword = document.getElementById('add-password');
            const addUsernameError = document.getElementById('add-username-error');
            const addEmailError = document.getElementById('add-email-error');
            const addPasswordError = document.getElementById('add-password-error');
            
            // Ouvrir le modal d'ajout
            addUserButton.addEventListener('click', function() {
                // Réinitialiser le formulaire
                addUserForm.reset();
                
                // Réinitialiser les erreurs
                addUsernameError.classList.add('hidden');
                addEmailError.classList.add('hidden');
                addPasswordError.classList.add('hidden');
                
                // Afficher le modal
                addUserModal.classList.remove('hidden');
            });
            
            // Fermer le modal d'ajout (pour tous les boutons de fermeture)
            addUserModal.querySelectorAll('.modal-close').forEach(button => {
                button.addEventListener('click', function() {
                    addUserModal.classList.add('hidden');
                });
            });
            
            // Gestion du clic en dehors du modal pour le fermer
            addUserModal.addEventListener('click', function(event) {
                if (event.target === addUserModal) {
                    addUserModal.classList.add('hidden');
                }
            });
            
            // Soumission du formulaire d'ajout
            addUserForm.addEventListener('submit', function(event) {
                event.preventDefault();
                
                // Réinitialiser les erreurs
                addUsernameError.classList.add('hidden');
                addEmailError.classList.add('hidden');
                addPasswordError.classList.add('hidden');
                
                // Récupérer les données du formulaire
                const username = addUsername.value.trim();
                const email = addEmail.value.trim();
                const password = addPassword.value.trim();
                
                // Envoyer les données
                fetch('<?= $viewData['basePath'] ?>/admin/users/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: username,
                        email: email,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fermer le modal
                        addUserModal.classList.add('hidden');
                        
                        // Afficher la notification de succès
                        showNotification('Utilisateur créé avec succès', 'success');
                        
                        // Ajouter l'utilisateur au tableau
                        const userTable = document.querySelector('#users-section table tbody');
                        if (userTable) {
                            const newRow = document.createElement('tr');
                            newRow.className = 'hover:bg-gray-50';
                            newRow.dataset.userId = data.user.id;
                            
                            newRow.innerHTML = `
                                <td class="px-4 py-3">${data.user.id}</td>
                                <td class="px-4 py-3 font-medium">${username}</td>
                                <td class="px-4 py-3">${email}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Actif</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Utilisateur</span>
                                </td>
                                <td class="px-4 py-3 text-sm">${new Date().toLocaleDateString('fr-FR')}</td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2">
                                        <button class="deactivate-user text-yellow-600 hover:text-yellow-900" title="Désactiver">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                        <button class="edit-user text-blue-600 hover:text-blue-900" title="Modifier" data-user-id="${data.user.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-user text-red-600 hover:text-red-900" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            `;
                            
                            userTable.appendChild(newRow);
                            
                            // Attacher les écouteurs d'événements aux nouveaux boutons
                            const newDeactivateButton = newRow.querySelector('.deactivate-user');
                            const newEditButton = newRow.querySelector('.edit-user');
                            const newDeleteButton = newRow.querySelector('.delete-user');
                            
                            if (newDeactivateButton) {
                                newDeactivateButton.addEventListener('click', function() {
                                    const userId = this.closest('tr').dataset.userId;
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
                            }
                            
                            if (newEditButton) {
                                newEditButton.addEventListener('click', function() {
                                    const userId = this.dataset.userId;
                                    
                                    // Réinitialiser les erreurs
                                    usernameError.classList.add('hidden');
                                    emailError.classList.add('hidden');
                                    
                                    // Récupérer les données de l'utilisateur
                                    fetch('<?= $viewData['basePath'] ?>/admin/users/details/' + userId)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                // Remplir le formulaire
                                                editUserId.value = data.user.id;
                                                editUsername.value = data.user.username;
                                                editEmail.value = data.user.email;
                                                
                                                // Afficher le modal
                                                editUserModal.classList.remove('hidden');
                                            } else {
                                                showNotification(data.message || 'Erreur lors de la récupération des données', 'error');
                                            }
                                        })
                                        .catch(error => {
                                            showNotification('Erreur de communication avec le serveur', 'error');
                                            console.error('Error:', error);
                                        });
                                });
                            }
                            
                            if (newDeleteButton) {
                                newDeleteButton.addEventListener('click', function() {
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
                            }
                        } else {
                            // Si aucun tableau n'existe, recharger la page
                            setTimeout(() => { window.location.reload(); }, 1500);
                        }
                    } else {
                        // Afficher les erreurs
                        if (data.errors) {
                            data.errors.forEach(error => {
                                if (error.includes('nom d\'utilisateur')) {
                                    addUsernameError.textContent = error;
                                    addUsernameError.classList.remove('hidden');
                                } else if (error.includes('email')) {
                                    addEmailError.textContent = error;
                                    addEmailError.classList.remove('hidden');
                                } else if (error.includes('mot de passe')) {
                                    addPasswordError.textContent = error;
                                    addPasswordError.classList.remove('hidden');
                                }
                            });
                        } else {
                            showNotification(data.message || 'Erreur lors de la création', 'error');
                        }
                    }
                })
                .catch(error => {
                    showNotification('Erreur de communication avec le serveur', 'error');
                    console.error('Error:', error);
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