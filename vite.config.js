import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Your main Tailwind entry point
                'resources/js/app.js',
                'resources/css/filament/app/theme.css', // Your custom Filament theme
            ],
            refresh: true,
        }),
    ],
});