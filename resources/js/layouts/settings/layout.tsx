import Heading from '@/components/common/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { cn, toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editMedicalFile } from '@/routes/medical-file';
import { edit } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title_path: 'main.settings_layout.sidebar_items.profile',
        href: edit(),
        icon: null,
    },
    {
        title_path: 'main.settings_layout.sidebar_items.medical_file',
        href: editMedicalFile(),
        icon: null,
    },
    {
        title_path: 'main.settings_layout.sidebar_items.security',
        href: editSecurity(),
        icon: null,
    },
    {
        title_path: 'main.settings_layout.sidebar_items.appearance',
        href: editAppearance(),
        icon: null,
    },
];

export default function SettingsLayout({ children }: Readonly<PropsWithChildren>) {
    const { __ } = lang();
    const { isCurrentOrParentUrl } = useCurrentUrl();

    return (
        <div className="px-4 py-6">
            <Heading title={__('main.settings_layout.title')} description={__('main.settings_layout.description')} />

            <div className="flex flex-col lg:flex-row lg:space-x-12">
                <aside className="w-full max-w-xl lg:w-48">
                    <nav className="flex flex-col space-y-1 space-x-0" aria-label="Settings">
                        {sidebarNavItems.map((item, index) => (
                            <Button
                                key={`${toUrl(item.href)}-${index}`}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn('w-full justify-start', {
                                    'bg-muted': isCurrentOrParentUrl(item.href),
                                })}
                            >
                                <Link href={item.href}>
                                    {item.icon && <item.icon className="h-4 w-4" />}
                                    {__(item.title_path)}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="my-6 lg:hidden" />

                <div className="flex-1 md:max-w-2xl">
                    <section className="max-w-xl space-y-12">{children}</section>
                </div>
            </div>
        </div>
    );
}
