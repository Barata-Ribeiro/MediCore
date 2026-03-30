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
};

export default function ActionConfirmationDialog(props: Readonly<Props>) {
    const { title, description, open, setOpen, method, route } = props;

    return (
        <AlertDialog open={open} onOpenChange={setOpen}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{title}</AlertDialogTitle>
                    <AlertDialogDescription>{description}</AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel onClick={() => setOpen(false)}>Cancel</AlertDialogCancel>
                    <AlertDialogAction asChild>
                        <Link href={route} method={method} as="button">
                            Confirm
                        </Link>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
