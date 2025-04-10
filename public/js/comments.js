console.log('=== Script de commentaires chargé ===');

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM chargé ===');
    const form = document.getElementById('comment-form');
    console.log('Formulaire trouvé:', form);

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const content = this.querySelector('textarea').value;
        const itemId = this.dataset.itemId;
        const itemType = this.dataset.itemType;

        console.log('Envoi commentaire:', { 
            content, 
            itemId, 
            itemType,
            formAction: form.action,
            formMethod: form.method
        });

        try {
            const response = await fetch('/add-comment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ content, item_id: itemId, item_type: itemType })
            });

            console.log('Status de la réponse:', response.status);
            const responseText = await response.text();
            console.log('Réponse brute:', responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Erreur parsing JSON:', e);
                throw new Error('Réponse invalide du serveur');
            }

            console.log('Réponse parsée:', data);

            if (data.status === 'success') {
                const commentsContainer = document.getElementById('comments-container');
                console.log('Container de commentaires trouvé:', commentsContainer);

                // Au lieu de recharger la page, on ajoute le nouveau commentaire
                const commentHtml = `
                    <div class="comment-item bg-gray-800 p-4 rounded" data-comment-id="${data.comment.id}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm mb-2">
                                    Par ${data.comment.username} • 
                                    ${new Date(data.comment.created_at).toLocaleString()}
                                </p>
                                <p class="text-white">${data.comment.content}</p>
                            </div>
                            <button class="delete-comment text-red-500 hover:text-red-700" 
                                    data-comment-id="${data.comment.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                // Si c'est le premier commentaire, on supprime le message "Aucun commentaire"
                const noCommentsMessage = commentsContainer.querySelector('.text-gray-400');
                if (noCommentsMessage && noCommentsMessage.textContent.includes('Aucun commentaire')) {
                    noCommentsMessage.remove();
                }

                // Ajouter le nouveau commentaire au début de la liste
                commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
                
                // Réinitialiser le formulaire
                this.querySelector('textarea').value = '';
            } else {
                alert(data.message || 'Erreur lors de l\'ajout du commentaire');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi du commentaire');
        }
    });

    // Gestion de la suppression des commentaires
    document.addEventListener('click', async function(e) {
        const deleteButton = e.target.closest('.delete-comment');
        if (!deleteButton) return;

        e.preventDefault();
        
        const commentId = deleteButton.dataset.commentId;
        console.log('Tentative de suppression du commentaire:', commentId);
        
        if (confirm('Voulez-vous vraiment supprimer ce commentaire ?')) {
            try {
                const response = await fetch('/delete-comment', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ comment_id: commentId })
                });

                console.log('Status de la réponse de suppression:', response.status);
                const responseText = await response.text();
                console.log('Réponse brute de suppression:', responseText);

                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                    throw new Error('Réponse invalide du serveur');
                }

                if (data.status === 'success') {
                    // Supprimer le commentaire du DOM
                    const commentElement = deleteButton.closest('.comment-item');
                    commentElement.remove();
                } else {
                    alert(data.message || 'Erreur lors de la suppression du commentaire');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression du commentaire');
            }
        }
    });
});