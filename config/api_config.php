<?php
define('API_KEY', 'c21ac6ce8a090027847698c1f58d5a71');
define('API_BASE_URL', 'https://api.themoviedb.org/3');
define('API_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p');
define('API_LANGUAGE', 'fr-FR');

// Tailles d'images disponibles
define('IMAGE_SIZES', [
    'poster' => [
        'small' => 'w185',
        'medium' => 'w342',
        'large' => 'w500'
    ],
    'backdrop' => [
        'small' => 'w300',
        'medium' => 'w780',
        'large' => 'w1280',
        'original' => 'original'
    ]
]); 