import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { cn, toUrl } from '@/lib/utils';
import type { NavItem } from '@/types';
import { lang } from '@erag/lang-sync-inertia/react';
import type { ComponentPropsWithoutRef } from 'react';

export function NavFooter({
    items,
    className,
    ...props
}: ComponentPropsWithoutRef<typeof SidebarGroup> & {
    items: NavItem[];
}) {
    const { __ } = lang();

    return (
        <SidebarGroup {...props} className={cn('group-data-[collapsible=icon]:p-0', className)}>
            <SidebarGroupContent>
                <SidebarMenu>
                    {items.map((item) => (
                        <SidebarMenuItem key={item.title_path}>
                            <SidebarMenuButton
                                asChild
                                className="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100"
                            >
                                <a href={toUrl(item.href)} target="_blank" rel="noopener noreferrer">
                                    {item.icon && <item.icon aria-hidden className="size-5" />}
                                    <span>{__(item.title_path)}</span>
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    ))}
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
