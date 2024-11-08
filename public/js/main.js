document.addEventListener('DOMContentLoaded', () => {
    console.log('Main.js script loaded');

    // Exemple de gestionnaire d'événements pour les liens de navigation
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            console.log(`Navigating to ${event.target.href}`);
            // Ajoutez ici des animations ou des transitions si nécessaire
        });
    });

    // Autres scripts généraux peuvent être ajoutés ici
}); 