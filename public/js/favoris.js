document.addEventListener('DOMContentLoaded', function() {
    const favoriButtons = document.querySelectorAll('.favori-button');
    
    favoriButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const mediaType = this.dataset.mediaType;
            const isFavori = this.classList.contains('active');
            
            if (isFavori) {
                removeFavori(itemId, mediaType, this);
            } else {
                addFavori(itemId, mediaType, this);
            }
        });
    });
});

function addFavori(itemId, mediaType, button) {
    fetch('/Cinetech/favoris/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            item_id: itemId,
            media_type: mediaType
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
            button.classList.add('active');
            showNotification('Favori ajouté avec succès', 'success');
        } else if (data.status === 'guest') {
            window.location.href = '/Cinetech/login';
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur est survenue lors de l\'ajout aux favoris', 'error');
    });
}

function removeFavori(itemId, mediaType, button) {
    fetch('/Cinetech/favoris/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            item_id: itemId,
            media_type: mediaType
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
            button.classList.remove('active');
            showNotification('Favori supprimé avec succès', 'success');
        } else if (data.status === 'guest') {
            window.location.href = '/Cinetech/login';
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur est survenue lors de la suppression du favori', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}