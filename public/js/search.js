document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('search-results');
    let debounceTimer;

    function debounce(func, delay) {
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    function createPlaceholder(title) {
        const placeholder = document.createElement('div');
        placeholder.classList.add('w-12', 'h-18', 'bg-gray-700', 'rounded', 'mr-3', 'flex', 'items-center', 'justify-center');
        
        // Extraire les initiales du titre
        const initials = title.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2);
        const text = document.createElement('span');
        text.textContent = initials;
        text.classList.add('text-white', 'text-sm', 'font-bold');
        
        placeholder.appendChild(text);
        return placeholder;
    }

    const performSearch = debounce(function(query) {
        if (query.length >= 2) {
            fetch(`/search/autocomplete?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.remove('hidden');

                    if (data.results && data.results.length > 0) {
                        data.results.forEach(item => {
                            if ((item.title || item.name).toLowerCase().startsWith(query.toLowerCase())) {
                                const resultItem = document.createElement('div');
                                resultItem.classList.add('flex', 'items-center', 'p-2', 'hover:bg-gray-700', 'cursor-pointer');

                                // Vérifier si l'image est disponible
                                if (item.poster_path) {
                                    const img = new Image();
                                    img.src = `https://image.tmdb.org/t/p/w92${item.poster_path}`;
                                    img.alt = item.title || item.name;
                                    img.classList.add('w-12', 'h-18', 'object-cover', 'rounded', 'mr-3');

                                    // Gestion des erreurs d'image
                                    img.onerror = function() {
                                        this.onerror = null; // Évite une boucle infinie
                                        this.replaceWith(createPlaceholder(item.title || item.name)); // Remplace par le placeholder
                                    };

                                    resultItem.appendChild(img);
                                } else {
                                    // Si pas d'image, utiliser le placeholder
                                    resultItem.appendChild(createPlaceholder(item.title || item.name));
                                }

                                const textContainer = document.createElement('div');
                                textContainer.classList.add('flex', 'flex-col');

                                const title = document.createElement('span');
                                title.textContent = item.title || item.name;
                                title.classList.add('text-white', 'text-sm', 'font-semibold');

                                const year = document.createElement('span');
                                year.textContent = new Date(item.release_date || item.first_air_date).getFullYear() || 'Année inconnue';
                                year.classList.add('text-gray-400', 'text-xs');

                                textContainer.appendChild(title);
                                textContainer.appendChild(year);

                                resultItem.appendChild(textContainer);

                                resultItem.onclick = function() {
                                    window.location.href = `/detail/${item.media_type}/${item.id}`;
                                };

                                resultsContainer.appendChild(resultItem);
                            }
                        });
                    } else {
                        resultsContainer.innerHTML = '<div class="p-2 text-gray-400 text-sm">Aucun résultat trouvé</div>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    resultsContainer.innerHTML = '<div class="p-2 text-red-500 text-sm">Une erreur est survenue</div>';
                });
        } else {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('hidden');
        }
    }, 300); // 300ms de délai pour le debounce

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        performSearch(query);
    });

    // Cacher les résultats si clic en dehors
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
});