document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing favori buttons...');
    const favoriButtons = document.querySelectorAll('.favori-button');
    console.log('Found favori buttons:', favoriButtons.length);
    
    favoriButtons.forEach(button => {
        console.log('Setting up button:', button);
        console.log('Button data:', {
            itemId: button.dataset.itemId,
            mediaType: button.dataset.mediaType,
            isActive: button.classList.contains('active')
        });
        
        // Supprimer l'ancien événement s'il existe
        button.removeEventListener('click', handleFavoriClick);
        
        // Ajouter le nouvel événement
        button.addEventListener('click', handleFavoriClick);
    });
});

// Fonction globale pour gérer le clic sur les boutons de favoris
window.handleFavoriClick = function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const button = this;
    const itemId = button.dataset.itemId;
    const mediaType = button.dataset.mediaType;
    const isFavori = button.classList.contains('active');
    const heartIcon = button.querySelector('i');
    
    console.log('Button clicked:', {
        itemId,
        mediaType,
        isFavori,
        classList: button.classList.toString(),
        button: button
    });
    
    if (isFavori) {
        removeFavori(itemId, mediaType, button, heartIcon);
    } else {
        addFavori(itemId, mediaType, button, heartIcon);
    }
}

function addFavori(itemId, mediaType, button, heartIcon) {
    console.log('Adding favori:', {
        itemId,
        mediaType,
        buttonClasses: button.classList.toString()
    });

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
        console.log('Add response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Add response data:', data);
        if (data.status === 'success') {
            button.classList.add('active');
            heartIcon.classList.remove('far');
            heartIcon.classList.add('fas');
            showNotification('Favori ajouté avec succès', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
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

function removeFavori(itemId, mediaType, button, heartIcon) {
    console.log('Removing favori:', {
        itemId,
        mediaType,
        buttonClasses: button.classList.toString()
    });

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
        console.log('Remove response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Remove response data:', data);
        if (data.status === 'success') {
            button.classList.remove('active');
            heartIcon.classList.remove('fas');
            heartIcon.classList.add('far');
            showNotification('Favori supprimé avec succès', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
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
    console.log('Showing notification:', { message, type });
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}