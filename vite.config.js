import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Agar Vite bisa diakses dari luar kontainer
        hmr: {
            host: 'localhost', // Agar Hot Module Replacement mengarah ke laptopmu
        },
    },
});
