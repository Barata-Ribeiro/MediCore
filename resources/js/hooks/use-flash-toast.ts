import { useIsomorphicLayoutEffect } from '@/hooks/use-isomorphic-layout-effect';
import type { FlashToast } from '@/types/ui';
import { router } from '@inertiajs/react';
import { toast } from 'sonner';

export function useFlashToast(): void {
    useIsomorphicLayoutEffect(() => {
        return router.on('flash', (event) => {
            const flash = (event as CustomEvent).detail?.flash;
            const data = flash?.toast as FlashToast | undefined;

            if (!data) {
                return;
            }

            toast[data.type](data.message);
        });
    }, []);
}
