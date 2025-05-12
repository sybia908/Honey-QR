import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Initialize Alpine.js
document.addEventListener('DOMContentLoaded', function() {
    // Mendaftarkan fungsi dark mode global
    window.toggleDarkMode = function() {
        const darkMode = document.documentElement.classList.contains('dark');
        if (darkMode) {
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('darkMode', 'false');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('darkMode', 'true');
        }
    };
    
    // Inisialisasi dark mode dari localStorage
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    if (savedDarkMode) {
        document.documentElement.classList.add('dark');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
    }
});

// Start Alpine.js
Alpine.start();
