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
import type { LucideIcon } from 'lucide-react';
import { Monitor, Moon, Sun } from 'lucide-react';

type AppearanceTab = {
    value: Appearance;
    icon: LucideIcon;
    label: string;
};

export default function AppearanceMenu() {
    const { appearance, updateAppearance } = useAppearance();

    const tabs: AppearanceTab[] = [
        { value: 'light', icon: Sun, label: 'Light' },
        { value: 'dark', icon: Moon, label: 'Dark' },
        { value: 'system', icon: Monitor, label: 'System' },
    ];

    const ActiveIcon = tabs.find((tab) => tab.value === appearance)?.icon;

    if (!ActiveIcon) {
        return null;
    }

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline" size="icon" aria-label="Select Appearance" title="Select Appearance">
                    <ActiveIcon aria-hidden className="size-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
                <DropdownMenuGroup>
                    <DropdownMenuLabel>Select Appearance</DropdownMenuLabel>
                    <DropdownMenuRadioGroup
                        value={appearance}
                        onValueChange={(value) => updateAppearance(value as Appearance)}
                    >
                        {tabs.map(({ value, icon: Icon, label }) => (
                            <DropdownMenuRadioItem key={value} value={value}>
                                <Icon aria-hidden className="size-4" />
                                <span className="ml-2">{label}</span>
                            </DropdownMenuRadioItem>
                        ))}
                    </DropdownMenuRadioGroup>
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
