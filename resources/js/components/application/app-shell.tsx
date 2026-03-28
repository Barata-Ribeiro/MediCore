import { SidebarProvider } from '@/components/ui/sidebar';
import useIsMounted from '@/hooks/use-mounted';
import type { AppVariant, FlashPayload } from '@/types';
import { usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { useEffect, useMemo } from 'react';
import { toast } from 'sonner';

type Props = {
    children: ReactNode;
    variant?: AppVariant;
};

const showFlashToast = (flash: FlashPayload) => {
    if (!flash || !Object.values(flash).some(Boolean)) {
        return;
    }

    for (const [level, message] of Object.entries(flash)) {
        if (!message) {
            continue;
        }

        switch (level) {
            case 'success':
                toast.success('Success!', { description: String(message) });
                break;
            case 'error':
                toast.error('Error!', { description: String(message) });
                break;
            case 'warning':
                toast.warning('Warning!', { description: String(message) });
                break;
            case 'info':
                toast.info('Info!', { description: String(message) });
                break;
            default:
                toast(String(message));
                break;
        }
    }
};

export function AppShell({ children, variant = 'sidebar' }: Readonly<Props>) {
    const isMounted = useIsMounted();
    const page = usePage();

    const flash = useMemo(() => page.flash, [page.flash]);
    const isOpen = page.props.sidebarOpen;

    useEffect(() => {
        if (!isMounted) {
            return;
        }

        showFlashToast(flash as FlashPayload);

        return () => {
            toast.dismiss();
        };
    }, [flash, isMounted]);

    if (variant === 'header') {
        return <div className="flex min-h-screen w-full flex-col">{children}</div>;
    }

    return <SidebarProvider defaultOpen={isOpen}>{children}</SidebarProvider>;
}
