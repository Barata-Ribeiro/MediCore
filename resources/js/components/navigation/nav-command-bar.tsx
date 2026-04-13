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
import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/core';
import { formatForDisplay, useHotkey } from '@tanstack/react-hotkeys';
import { KeyRoundIcon, SearchIcon, SunMoonIcon, UserIcon } from 'lucide-react';
import { useCallback, useState } from 'react';

export default function NavCommandBar() {
    const [open, setOpen] = useState(false);

    const handleRouteChange = useCallback((href: NonNullable<string | UrlMethodPair | undefined>) => {
        return () => {
            router.get(href, { viewTransition: true });
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
                <span>Search...</span>

                <Kbd className="-mr-2 ml-18 hidden sm:inline-flex">{formatForDisplay('Mod+K')}</Kbd>
            </Button>
            <CommandDialog open={open} onOpenChange={setOpen}>
                <Command>
                    <CommandInput placeholder="Type something to search..." />

                    <CommandList>
                        <CommandEmpty>No results found.</CommandEmpty>

                        <CommandGroup heading="Main Navigation">
                            {mainNavItems
                                .filter((item) => !item.items || item.href !== '#')
                                .map((item) => (
                                    <CommandItem key={item.title} onSelect={handleRouteChange(item.href)}>
                                        {item.icon && <item.icon aria-hidden />}
                                        <span>{item.title}</span>
                                    </CommandItem>
                                ))}
                        </CommandGroup>
                        <CommandSeparator />
                        {mainNavItems
                            .filter((item) => item.items && item.href === '#')
                            .map((item) => (
                                <CommandGroup key={item.title} heading={item.title}>
                                    {item.items?.map((subItem) => (
                                        <CommandItem key={subItem.title} onSelect={handleRouteChange(subItem.href)}>
                                            <span>{subItem.title}</span>
                                        </CommandItem>
                                    ))}
                                </CommandGroup>
                            ))}
                        <CommandSeparator />
                        <CommandGroup heading="Settings">
                            <CommandItem onSelect={handleRouteChange(edit())}>
                                <UserIcon aria-hidden />
                                <span>Profile</span>
                                <CommandShortcut>{formatForDisplay('Mod+P')}</CommandShortcut>
                            </CommandItem>

                            <CommandItem onSelect={handleRouteChange(editSecurity())}>
                                <KeyRoundIcon aria-hidden />
                                <span>Change Password</span>
                                <CommandShortcut>{formatForDisplay('Mod+Shift+P')}</CommandShortcut>
                            </CommandItem>

                            <CommandItem onSelect={handleRouteChange(editAppearance())}>
                                <SunMoonIcon aria-hidden />
                                <span>Appearance</span>
                                <CommandShortcut>{formatForDisplay('Mod+Shift+A')}</CommandShortcut>
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </CommandDialog>
        </div>
    );
}
