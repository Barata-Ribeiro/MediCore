import { UserInfo } from '@/components/navigation/user-info';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link, router } from '@inertiajs/react';
import { LogOutIcon, SettingsIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    user: User;
};

export function UserMenuContent({ user }: Readonly<Props>) {
    const { __ } = lang();

    const cleanup = useMobileNavigation();

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <Fragment>
            <DropdownMenuLabel className="p-0 font-normal">
                <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <UserInfo user={user} showEmail={true} />
                </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuGroup>
                <DropdownMenuItem asChild>
                    <Link className="block w-full" href={edit()} prefetch onClick={cleanup}>
                        <SettingsIcon aria-hidden className="mr-2" />
                        {__('main.menu.user_dropdown.settings')}
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuGroup>
            <DropdownMenuSeparator />
            <DropdownMenuItem variant="destructive" asChild>
                <Link
                    className="block w-full"
                    href={logout()}
                    as="button"
                    onClick={handleLogout}
                    data-test="logout-button"
                >
                    <LogOutIcon aria-hidden className="mr-2" />
                    {__('main.menu.user_dropdown.logout')}
                </Link>
            </DropdownMenuItem>
        </Fragment>
    );
}
