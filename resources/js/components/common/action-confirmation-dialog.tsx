import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import type { RouteDefinition } from '@/wayfinder';
import type { Method } from '@inertiajs/core';
import { Link } from '@inertiajs/react';
import type { Dispatch, SetStateAction } from 'react';

type Props = {
    title: string;
    description: string;
    open: boolean;
    setOpen: Dispatch<SetStateAction<boolean>>;
    method: Method;
    route: RouteDefinition<Method>;
    cancelLabel?: string;
    confirmLabel?: string;
};

export default function ActionConfirmationDialog(props: Readonly<Props>) {
    const {
        title,
        description,
        open,
        setOpen,
        method,
        route,
        cancelLabel = 'Cancel',
        confirmLabel = 'Confirm',
    } = props;

    return (
        <AlertDialog open={open} onOpenChange={setOpen}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{title}</AlertDialogTitle>
                    <AlertDialogDescription>{description}</AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel onClick={() => setOpen(false)}>{cancelLabel}</AlertDialogCancel>
                    <AlertDialogAction
                        nativeButton={false}
                        render={
                            <Link href={route} method={method} as="button">
                                {confirmLabel}
                            </Link>
                        }
                    />
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
