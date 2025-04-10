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
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            button.classList.add('active');
            showNotification('Favori ajouté avec succès', 'success');
        } else if (data.status === 'guest') {
            window.location.href = '/Cinetech/login';
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur est survenue', 'error');
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
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            button.classList.remove('active');
            showNotification('Favori supprimé avec succès', 'success');
        } else if (data.status === 'guest') {
            window.location.href = '/Cinetech/login';
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur est survenue', 'error');
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