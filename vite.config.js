import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'], // Určuje vstupné súbory
            refresh: true, // Povolenie auto-refresh pri zmene súborov
        }),
    ],
});
