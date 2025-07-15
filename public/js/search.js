document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, initializing search functionality...');
    
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('search-results');
    
    if (!searchInput) {
        console.error('Search input not found!');
        return;
    }
    
    if (!resultsContainer) {
        console.error('Results container not found!');
        return;
    }
    
    console.log('Search elements found:', { searchInput, resultsContainer });
    
    // Récupérer le chemin de base et les traductions depuis des attributs data
    const basePath = document.body.getAttribute('data-base-path') || '/Cinetech';
    const translations = {
        searchInProgress: document.body.getAttribute('data-translation-search-in-progress') || 'Recherche en cours pour "{query}"...',
        noResults: document.body.getAttribute('data-translation-no-results') || 'Aucun résultat trouvé',
        errorOccurred: document.body.getAttribute('data-translation-error-occurred') || 'Une erreur est survenue',
        yearUnknown: document.body.getAttribute('data-translation-year-unknown') || 'Année inconnue',
        minChars: document.body.getAttribute('data-translation-min-chars') || 'Veuillez saisir au moins 2 caractères'
    };
    
    console.log('Translations loaded:', translations);
    
    // Ajouter des événements d'affichage direct
    searchInput.addEventListener('click', function() {
        console.log("Search input clicked in search.js");
        if (this.value.trim().length >= 2) {
            resultsContainer.classList.remove('hidden');
        }
    });
    
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
            console.log('Searching for:', query);
            console.log('Using base path:', basePath);
            
            fetch(`${basePath}/search/autocomplete?query=${encodeURIComponent(query)}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Search results:', data);
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.remove('hidden');

                    if (data.results && data.results.length > 0) {
                        console.log(`Found ${data.results.length} results`);
                        data.results.forEach(item => {
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
                            if (item.release_date || item.first_air_date) {
                                try {
                                    year.textContent = new Date(item.release_date || item.first_air_date).getFullYear();
                                } catch (e) {
                                    console.error('Error parsing date:', e);
                                    year.textContent = translations.yearUnknown;
                                }
                            } else {
                                year.textContent = translations.yearUnknown;
                            }
                            year.classList.add('text-gray-400', 'text-xs');

                            textContainer.appendChild(title);
                            textContainer.appendChild(year);

                            resultItem.appendChild(textContainer);

                            resultItem.onclick = function() {
                                window.location.href = `${basePath}/detail/${item.media_type}/${item.id}`;
                            };

                            resultsContainer.appendChild(resultItem);
                        });
                    } else {
                        resultsContainer.innerHTML = `<div class="p-2 text-gray-400 text-sm">${translations.noResults}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    resultsContainer.innerHTML = `<div class="p-2 text-red-500 text-sm">${translations.errorOccurred}</div>`;
                });
        } else {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('hidden');
        }
    }, 300); // 300ms de délai pour le debounce

    // Ajouter un event listener pour l'input
    console.log('Adding input event listener to search input');
    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        console.log('Search input value changed:', query);
        performSearch(query);
    });

    // Ajouter un event listener pour le focus
    console.log('Adding focus event listener to search input');
    searchInput.addEventListener('focus', function () {
        const query = this.value.trim();
        console.log('Search input focused, value:', query);
        if (query.length >= 2) {
            performSearch(query);
        }
    });

    // Cacher les résultats si clic en dehors
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
    
    console.log('Search functionality initialized successfully');
});