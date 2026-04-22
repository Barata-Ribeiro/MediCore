import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuLabel,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Appearance } from '@/hooks/use-appearance';
import { useAppearance } from '@/hooks/use-appearance';
import { lang } from '@erag/lang-sync-inertia/react';
import type { LucideIcon } from 'lucide-react';
import { Monitor, Moon, Sun } from 'lucide-react';

type AppearanceTab = {
    value: Appearance;
    icon: LucideIcon;
    label_path: string;
};

export default function AppearanceMenu() {
    const { appearance, updateAppearance } = useAppearance();
    const { __ } = lang();

    const tabs: AppearanceTab[] = [
        { value: 'light', icon: Sun, label_path: 'main.appearance_dropdown.light' },
        { value: 'dark', icon: Moon, label_path: 'main.appearance_dropdown.dark' },
        { value: 'system', icon: Monitor, label_path: 'main.appearance_dropdown.system' },
    ];

    const ActiveIcon = tabs.find((tab) => tab.value === appearance)?.icon;

    if (!ActiveIcon) {
        return null;
    }

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    size="icon"
                    aria-label={__('main.appearance_dropdown.title')}
                    title={__('main.appearance_dropdown.title')}
                >
                    <ActiveIcon aria-hidden className="size-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
                <DropdownMenuGroup>
                    <DropdownMenuLabel>{__('main.appearance_dropdown.title')}</DropdownMenuLabel>
                    <DropdownMenuRadioGroup
                        value={appearance}
                        onValueChange={(value) => updateAppearance(value as Appearance)}
                    >
                        {tabs.map(({ value, icon: Icon, label_path }) => (
                            <DropdownMenuRadioItem key={value} value={value}>
                                <Icon aria-hidden className="size-4" />
                                <span className="ml-2">{__(label_path)}</span>
                            </DropdownMenuRadioItem>
                        ))}
                    </DropdownMenuRadioGroup>
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
