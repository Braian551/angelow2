import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/shared/base.css',
                'resources/js/shared/utils.js',
                'resources/css/modules/catalog/index.css',
                'resources/js/modules/catalog/index.js',
                'resources/css/modules/sales/index.css',
                'resources/js/modules/sales/index.js',
                'resources/css/modules/customers/index.css',
                'resources/js/modules/customers/index.js',
                'resources/css/modules/backoffice/index.css',
                'resources/js/modules/backoffice/index.js',
                'resources/css/modules/notifications/index.css',
                'resources/js/modules/notifications/index.js',
                'resources/css/modules/content/index.css',
                'resources/js/modules/content/index.js',
                'resources/css/modules/support/index.css',
                'resources/js/modules/support/index.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
