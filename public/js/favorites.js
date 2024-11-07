async function toggleFavorite(event, mediaId, mediaType) {
    event.preventDefault();
    event.stopPropagation();
    
    try {
        const response = await fetch('/Cinetech/api/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                media_id: mediaId, 
                media_type: mediaType 
            })
        });

        const data = await response.json();
        
        if (data.success) {
            const icon = event.currentTarget.querySelector('i');
            if (data.isFavorite) {
                icon.classList.remove('text-white');
                icon.classList.add('text-red-600');
            } else {
                icon.classList.remove('text-red-600');
                icon.classList.add('text-white');
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

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