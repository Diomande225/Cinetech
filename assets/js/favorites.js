class FavoriteManager {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', this.handleFavoriteClick.bind(this));
        });

        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', this.handleRemoveFavorite.bind(this));
        });
    }

    async handleFavoriteClick(event) {
        const button = event.currentTarget;
        const contentId = button.dataset.id;
        const contentType = button.dataset.type;

        try {
            const response = await this.toggleFavorite(contentId, contentType);
            if (response.success) {
                button.classList.toggle('active');
                this.showNotification('Favoris mis à jour');
            }
        } catch (error) {
            this.showNotification('Une erreur est survenue', 'error');
        }
    }

    async handleRemoveFavorite(event) {
        const button = event.currentTarget;
        const contentId = button.dataset.id;
        const contentType = button.dataset.type;

        if (confirm('Voulez-vous vraiment retirer cet élément de vos favoris ?')) {
            try {
                const response = await this.toggleFavorite(contentId, contentType);
                if (response.success) {
                    const card = button.closest('.favorite-card');
                    card.remove();
                    this.showNotification('Élément retiré des favoris');
                }
            } catch (error) {
                this.showNotification('Une erreur est survenue', 'error');
            }
        }
    }

    async toggleFavorite(contentId, contentType) {
        const response = await fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ content_id: contentId, content_type: contentType })
        });

        return await response.json();
    }

    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new FavoriteManager();
}); 