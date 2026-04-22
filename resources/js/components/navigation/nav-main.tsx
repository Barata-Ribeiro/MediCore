import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import type { NavItem } from '@/types';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

export function NavMain({ items = [] }: Readonly<{ items: NavItem[] }>) {
    const { isCurrentUrl } = useCurrentUrl();
    const { __ } = lang();

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>{__('main.menu.sidebar_group_label')}</SidebarGroupLabel>
            <SidebarMenu>
                {items.map((item) => {
                    if (!item.items || item.href !== '#') {
                        return (
                            <SidebarMenuItem key={item.title_path}>
                                <SidebarMenuButton
                                    asChild
                                    isActive={isCurrentUrl(item.href)}
                                    tooltip={{ children: __(item.title_path) }}
                                >
                                    <Link href={item.href} prefetch>
                                        {item.icon && <item.icon aria-hidden />}
                                        <span>{__(item.title_path)}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        );
                    }

                    return (
                        <Collapsible
                            key={item.title_path}
                            asChild
                            defaultOpen={item.isActive}
                            className="group/collapsible"
                        >
                            <SidebarMenuItem>
                                <CollapsibleTrigger asChild>
                                    <SidebarMenuButton tooltip={__(item.title_path)}>
                                        {item.icon && <item.icon />}
                                        <span>{__(item.title_path)}</span>
                                        <ChevronRight className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                    </SidebarMenuButton>
                                </CollapsibleTrigger>
                                <CollapsibleContent>
                                    <SidebarMenuSub>
                                        {item.items?.map((subItem) => (
                                            <SidebarMenuSubItem key={subItem.title_path}>
                                                <SidebarMenuSubButton isActive={isCurrentUrl(subItem.href)} asChild>
                                                    <Link href={subItem.href} prefetch>
                                                        <span>{__(subItem.title_path)}</span>
                                                    </Link>
                                                </SidebarMenuSubButton>
                                            </SidebarMenuSubItem>
                                        ))}
                                    </SidebarMenuSub>
                                </CollapsibleContent>
                            </SidebarMenuItem>
                        </Collapsible>
                    );
                })}
            </SidebarMenu>
        </SidebarGroup>
    );
}
