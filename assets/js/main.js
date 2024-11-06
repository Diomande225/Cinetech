document.addEventListener('DOMContentLoaded', function() {
    // Gestion des favoris
    const favoriteBtns = document.querySelectorAll('.favorite-btn');
    favoriteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const contentId = this.dataset.id;
            const contentType = this.dataset.type;
            
            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    content_id: contentId,
                    content_type: contentType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('active');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Gestion de la barre de recherche mobile
    const searchToggle = document.querySelector('.search-toggle');
    const searchBar = document.querySelector('.search-bar');
    
    if (searchToggle) {
        searchToggle.addEventListener('click', function() {
            searchBar.classList.toggle('active');
        });
    }
}); 