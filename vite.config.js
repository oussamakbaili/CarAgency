import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        // En dev, servir en HTTPS pour éviter le contenu mixte si la page est en HTTPS
        https: true,
        host: true,
    },
});
