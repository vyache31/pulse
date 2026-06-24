import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
				'resources/css/app.css',
				'resources/js/kanban.js',
				'resources/css/public.css'
			],
            refresh: true,
        }),
    ],
});
