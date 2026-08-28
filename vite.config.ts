import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import babel from '@rolldown/plugin-babel';
import tailwindcss from '@tailwindcss/vite';
import react, { reactCompilerPreset } from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig, lazyPlugins } from 'vite-plus';

export default defineConfig({
    plugins: lazyPlugins(() => [
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
    ]),
    build: {
        target: 'esnext',
        minify: 'oxc',
        chunkSizeWarningLimit: 1000,
        rolldownOptions: {
            output: {
                entryFileNames: '[hash].js',
                chunkFileNames: '[hash].js',
                assetFileNames: '[hash].[ext]',
                minify: true,
            },
        },
        cssCodeSplit: true,
        sourcemap: false,
        assetsInlineLimit: 4096,
    },
    server: {
        host: 'medicore.test',
        port: 5173,
        hmr: { host: 'medicore.test', overlay: false },
        watch: {
            usePolling: false,
            ignored: ['**/.agents/**', '**/.claude/**', '**/.cursor/**', '**/.junie/**', '**/vendor/**'],
        },
    },
    lint: {
        ignorePatterns: [
            'vendor/**',
            'node_modules/**',
            'public/**',
            'bootstrap/ssr/**',
            'tailwind.config.js',
            'resources/js/actions/**',
            'resources/js/components/ui/*',
            'resources/js/routes/**',
            'resources/js/wayfinder/**',
        ],
        options: {
            denyWarnings: true,
            typeAware: true,
        },
    },
    fmt: {
        printWidth: 120,
        tabWidth: 4,
        singleQuote: true,
        semi: true,
        singleAttributePerLine: false,
        htmlWhitespaceSensitivity: 'css',
        ignorePatterns: ['.github/**', 'resources/js/components/ui/*', 'resources/views/mail/*'],
        sortTailwindcss: {
            functions: ['clsx', 'cn', 'cva'],
            entryPoint: 'resources/css/app.css',
        },
    },
    assetsInclude: ['**/*.{woff,woff2,eot,ttf,otf,svg,png,jpg,jpeg,gif,webp,avif}'],
});
