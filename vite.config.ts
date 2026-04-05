import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import babel from '@rolldown/plugin-babel';
import tailwindcss from '@tailwindcss/vite';
import react, { reactCompilerPreset } from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            detectTls: true,
            refresh: true,
        }),
        inertia({ ssr: { cluster: true } }),
        react(),
        babel({ presets: [reactCompilerPreset()] }),
        tailwindcss(),
        wayfinder({ formVariants: true }),
    ],
    server: {
        host: 'medicore.test',
        port: 5173,
        hmr: {
            host: 'medicore.test',
        },
    },
});
