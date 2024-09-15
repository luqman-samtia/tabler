import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
// import path from 'path';

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
        hmr: {
            host: 'localhost',
            protocol: 'ws',
            port: 3000
        }
    },
    resolve: {
        alias: {
            // Alias for Bootstrap (if needed)
            // '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            // // Alias for JsVectorMap (if needed)
            // '~jsvectormap': path.resolve(__dirname, 'node_modules/jsvectormap'),
            // Alias for Tabler Core JS
            // '@tabler': path.resolve(__dirname, 'node_modules/@tabler/core/src/js'),
        },
    },
    build: {
        commonjsOptions: {
            transformMixedEsModules: true
        }
    }
});
