import { Button } from '@/components/ui/button';
import {
    Command,
    CommandDialog,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
    CommandShortcut,
} from '@/components/ui/command';
import { Kbd } from '@/components/ui/kbd';
import { mainNavItems } from '@/lib/navigation-items';
import { edit as editAppearance } from '@/routes/appearance';
import { edit } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import { lang } from '@erag/lang-sync-inertia/react';
import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/core';
import { formatForDisplay, useHotkey } from '@tanstack/react-hotkeys';
import { KeyRoundIcon, SearchIcon, SunMoonIcon, UserIcon } from 'lucide-react';
import { useCallback, useState } from 'react';

export default function NavCommandBar() {
    const [open, setOpen] = useState(false);
    const { __ } = lang();

    const handleRouteChange = useCallback((href: NonNullable<string | UrlMethodPair | undefined>) => {
        return () => {
            if (typeof href === 'string') {
                router.get(href, { viewTransition: true });
            } else if (href) {
                router.visit(href.url, { method: href.method, viewTransition: true });
            }

            setOpen(false);
        };
    }, []);

    useHotkey('Mod+K', () => setOpen((open) => !open));
    useHotkey('Mod+P', handleRouteChange(edit()), { enabled: open });
    useHotkey('Mod+Shift+P', handleRouteChange(editSecurity()), { enabled: open });
    useHotkey('Mod+Shift+A', handleRouteChange(editAppearance()), { enabled: open });

    return (
        <div className="flex flex-col gap-4">
            <Button variant="outline" size="sm" className="text-muted-foreground" onClick={() => setOpen(true)}>
                <SearchIcon aria-hidden />
                <span>{__('main.command_bar.placeholder')}</span>

                <Kbd className="-mr-2 ml-18 hidden sm:inline-flex">{formatForDisplay('Mod+K')}</Kbd>
            </Button>
            <CommandDialog open={open} onOpenChange={setOpen}>
                <Command>
                    <CommandInput placeholder={__('main.command_bar.search_placeholder')} />

                    <CommandList>
                        <CommandEmpty>{__('main.command_bar.no_results')}</CommandEmpty>

                        <CommandGroup heading={__('main.command_bar.main_navigation')}>
                            {mainNavItems
                                .filter((item) => !item.items || item.href !== '#')
                                .map((item) => (
                                    <CommandItem key={item.title_path} onSelect={handleRouteChange(item.href)}>
                                        {item.icon && <item.icon aria-hidden />}
                                        <span>{__(item.title_path)}</span>
                                    </CommandItem>
                                ))}
                        </CommandGroup>
                        <CommandSeparator />
                        {mainNavItems
                            .filter((item) => item.items && item.href === '#')
                            .map((item) => (
                                <CommandGroup key={item.title_path} heading={__(item.title_path)}>
                                    {item.items?.map((subItem) => (
                                        <CommandItem
                                            key={subItem.title_path}
                                            onSelect={handleRouteChange(subItem.href)}
                                        >
                                            <span>{__(subItem.title_path)}</span>
                                        </CommandItem>
                                    ))}
                                </CommandGroup>
                            ))}
                        <CommandSeparator />
                        <CommandGroup heading="Settings">
                            <CommandItem onSelect={handleRouteChange(edit())}>
                                <UserIcon aria-hidden />
                                <span>{__('main.command_bar.settings_items.profile')}</span>
                                <CommandShortcut>{formatForDisplay('Mod+P')}</CommandShortcut>
                            </CommandItem>

                            <CommandItem onSelect={handleRouteChange(editSecurity())}>
                                <KeyRoundIcon aria-hidden />
                                <span>{__('main.command_bar.settings_items.change_password')}</span>
                                <CommandShortcut>{formatForDisplay('Mod+Shift+P')}</CommandShortcut>
                            </CommandItem>

                            <CommandItem onSelect={handleRouteChange(editAppearance())}>
                                <SunMoonIcon aria-hidden />
                                <span>{__('main.command_bar.settings_items.appearance')}</span>
                                <CommandShortcut>{formatForDisplay('Mod+Shift+A')}</CommandShortcut>
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </CommandDialog>
        </div>
    );
}
