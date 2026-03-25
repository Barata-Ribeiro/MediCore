import { initializeTheme } from '@/hooks/use-appearance';
import { createInertiaApp } from '@inertiajs/react';
import '../css/app.css';

createInertiaApp({
    strictMode: true,
    progress: { color: '#4B5563' },
    defaults: {
        prefetch: {
            cacheFor: '1m',
            hoverDelay: 150,
        },
    },
});

// This will set light / dark mode on load...
initializeTheme();
