class CommentManager {
    constructor() {
        this.commentForm = document.querySelector('.comment-form');
        this.commentsList = document.querySelector('.comments-list');
        this.loadMoreBtn = document.querySelector('.load-more-comments');
        this.currentPage = 1;

        this.initializeEventListeners();
    }

    initializeEventListeners() {
        if (this.commentForm) {
            this.commentForm.addEventListener('submit', this.handleCommentSubmit.bind(this));
        }

        if (this.loadMoreBtn) {
            this.loadMoreBtn.addEventListener('click', this.loadMoreComments.bind(this));
        }

        // Délégation d'événements pour les actions sur les commentaires
        this.commentsList.addEventListener('click', (e) => {
            if (e.target.matches('.reply-btn')) {
                this.handleReplyClick(e);
            } else if (e.target.matches('.edit-btn')) {
                this.handleEditClick(e);
            } else if (e.target.matches('.delete-btn')) {
                this.handleDeleteClick(e);
            }
        });
    }

    async handleCommentSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('/comments/add', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                this.addCommentToDOM(data.comment);
                form.reset();
            } else {
                this.showError(data.error);
            }
        } catch (error) {
            this.showError('Une erreur est survenue');
        }
    }

    async loadMoreComments() {
        const contentId = this.commentsList.dataset.contentId;
        const contentType = this.commentsList.dataset.contentType;
        this.currentPage++;

        try {
            const response = await fetch(`/comments/load?content_id=${contentId}&content_type=${contentType}&page=${this.currentPage}`);
            const data = await response.json();

            if (data.comments.length > 0) {
                data.comments.forEach(comment => {
                    this.addCommentToDOM(comment);
                });
            } else {
                this.loadMoreBtn.style.display = 'none';
            }
        } catch (error) {
            this.showError('Erreur lors du chargement des commentaires');
        }
    }

    addCommentToDOM(comment) {
        const commentElement = this.createCommentElement(comment);
        this.commentsList.insertBefore(commentElement, this.commentsList.firstChild);
    }

    createCommentElement(comment) {
        const div = document.createElement('div');
        div.className = 'comment';
        div.dataset.id = comment.id;

        div.innerHTML = `
            <div class="comment-header">
                <img src="${comment.avatar || '/assets/images/default-avatar.png'}" alt="Avatar" class="avatar">
                <strong>${comment.username}</strong>
                <span class="date">${this.formatDate(comment.created_at)}</span>
            </div>
            <div class="comment-content">
                <p>${comment.comment_text}</p>
            </div>
            <div class="comment-actions">
                <button class="reply-btn">Répondre</button>
                ${comment.can_edit ? `
                    <button class="edit-btn">Modifier</button>
                    <button class="delete-btn">Supprimer</button>
                ` : ''}
            </div>
            ${comment.replies ? `
                <div class="replies">
                    ${comment.replies.map(reply => this.createCommentElement(reply).outerHTML).join('')}
                </div>
            ` : ''}
        `;

        return div;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    showError(message) {
        const notification = document.createElement('div');
        notification.className = 'notification error';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new CommentManager();
}); 