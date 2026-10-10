import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

// Plain Vite; no client-framework or Laravel-specific Vite plugin.
export default defineConfig({
    publicDir: false,
    plugins: [tailwindcss()],
    build: {
        outDir: 'public/build',
        manifest: 'manifest.json',
        emptyOutDir: true,
        rollupOptions: {
            input: ['resources/css/admin.css', 'resources/js/admin.js'],
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        cors: { origin: /^http:\/\/(127\.0\.0\.1|localhost)(:\d+)?$/ },
        watch: { ignored: ['**/storage/**', '**/frontend/**', '**/docs/**'] },
    },
});
