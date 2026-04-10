import AppearanceMenu from '@/components/helpers/appearance-menu';
import { Breadcrumbs } from '@/components/helpers/breadcrumbs';
import NavCommandBar from '@/components/navigation/nav-command-bar';
import { Separator } from '@/components/ui/separator';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function AppSidebarHeader({ breadcrumbs = [] }: Readonly<{ breadcrumbs?: BreadcrumbItemType[] }>) {
    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="inline-flex items-center gap-x-2">
                <SidebarTrigger />
                <Separator orientation="vertical" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>

            <div className="ml-auto inline-flex items-center gap-x-2">
                <NavCommandBar />
                <AppearanceMenu />
            </div>
        </header>
    );
}
