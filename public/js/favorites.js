async function toggleFavorite(event, id, type) {
    event.preventDefault();
    
    if (!isAuthenticated) {
        window.location.href = '/Cinetech/login';
        return;
    }
    
    const button = event.currentTarget;
    
    try {
        const response = await fetch('/Cinetech/api/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id, type })
        });
        
        const data = await response.json();
        
        if (data.success) {
            button.classList.toggle('active');
            button.classList.add('animating');
            
            setTimeout(() => {
                button.classList.remove('animating');
            }, 500);
            
            updateFavoritesCount(data.count);
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