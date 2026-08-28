import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';
import ExamsLayout from '@/layouts/exams/layout';
import FitnessLayout from '@/layouts/fitness/layout';
import SettingsLayout from '@/layouts/settings/layout';
import { cn } from '@/lib/utils';
import { createInertiaApp } from '@inertiajs/react';
import { ModalStackProvider, putConfig } from '@inertiaui/modal-react';

const appName = import.meta.env['VITE_APP_NAME'] ?? 'Laravel';

void createInertiaApp({
    strictMode: true,
    title: (title) => `${title} - ${appName}`,
    layout: (name) => {
        switch (true) {
            case name === 'welcome':
            case name === 'error-page':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            case name.startsWith('exams/'):
                return [AppLayout, ExamsLayout];
            case name.startsWith('fitness/'):
                return [AppLayout, FitnessLayout];
            default:
                return AppLayout;
        }
    },
    withApp(app) {
        return (
            <TooltipProvider delay={0}>
                <ModalStackProvider>{app}</ModalStackProvider>
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

// Modal configuration
putConfig({
    type: 'modal',
    navigate: false,
    useNativeDialog: true,
    modal: {
        closeButton: false,
        closeExplicitly: true,
        closeOnClickOutside: true,
        maxWidth: 'full',
        paddingClasses: 'py-(--card-spacing)',
        panelClasses: cn`group/card bg-card text-card-foreground ring-foreground/5 dark:ring-foreground/10 flex flex-col gap-(--card-spacing) overflow-hidden rounded-[min(var(--radius-4xl),24px)] text-sm shadow-sm ring-1 [--card-spacing:--spacing(5)] has-[>img:first-child]:pt-0 *:[img:first-child]:rounded-t-[min(var(--radius-4xl),24px)] *:[img:last-child]:rounded-b-[min(var(--radius-4xl),24px)]`,
        position: 'center',
    },
    slideover: {
        closeButton: true,
        closeExplicitly: true,
        closeOnClickOutside: true,
        maxWidth: 'md',
        paddingClasses: 'p-4 sm:p-6',
        panelClasses: cn('bg-background min-h-screen rounded-none'),
        position: 'right',
    },
});
