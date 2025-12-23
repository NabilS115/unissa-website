import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
    build: {
        // Optimize asset bundling
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'],
                }
            }
        },
        // Optimize CSS
        cssCodeSplit: true,
        // Generate source maps for debugging
        sourcemap: true,
        // Optimize chunk size warnings
        chunkSizeWarningLimit: 1000
    },
    // Performance optimizations
    optimizeDeps: {
        include: ['alpinejs']
    }
});