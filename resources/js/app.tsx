import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';
import ExamsLayout from '@/layouts/exams/layout';
import SettingsLayout from '@/layouts/settings/layout';
import { createInertiaApp } from '@inertiajs/react';

const appName = import.meta.env['VITE_APP_NAME'] ?? 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    layout: (name) => {
        switch (true) {
            case name === 'welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            case name.startsWith('exams/'):
                return [AppLayout, ExamsLayout];
            default:
                return AppLayout;
        }
    },
    strictMode: true,
    withApp(app) {
        return (
            <TooltipProvider delayDuration={0}>
                {app}
                <Toaster duration={8000} position="top-right" richColors closeButton />
            </TooltipProvider>
        );
    },
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
