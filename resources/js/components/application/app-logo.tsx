import AppLogoIcon from '@/components/application/app-logo-icon';
import { usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function AppLogo() {
    const { name } = usePage().props;

    return (
        <Fragment>
            <div className="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
                <AppLogoIcon className="size-5 fill-current text-white dark:text-black" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="font-heading mb-0.5 truncate leading-tight font-semibold">{name}</span>
            </div>
        </Fragment>
    );
}
