document.addEventListener('DOMContentLoaded', () => {
    const favoriteButtons = document.querySelectorAll('.favorite-btn');

    favoriteButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const mediaId = button.dataset.mediaId;
            const mediaType = button.dataset.mediaType;

            try {
                const response = await fetch('/Cinetech/api/favorites/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        media_id: mediaId, 
                        media_type: mediaType 
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Erreur serveur');
                }

                const data = await response.json();
                if (data.success) {
                    const icon = button.querySelector('i.fa-heart');
                    if (data.isFavorite) {
                        icon.classList.remove('text-white');
                        icon.classList.add('text-red-600');
                    } else {
                        icon.classList.remove('text-red-600');
                        icon.classList.add('text-white');
                    }
                }
            } catch (error) {
                console.error('Erreur:', error.message);
            }
        });
    });
});

function updateFavoritesCount(count) {
    const favoritesCounter = document.querySelector('.favorites-count');
    if (favoritesCounter) {
        favoritesCounter.textContent = count;
        if (count > 0) {
            favoritesCounter.classList.remove('hidden');
        } else {
            favoritesCounter.classList.add('hidden');
        }
    }
} 