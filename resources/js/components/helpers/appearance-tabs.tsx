import type { Appearance } from '@/hooks/use-appearance';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { lang } from '@erag/lang-sync-inertia/react';
import type { LucideIcon } from 'lucide-react';
import { Monitor, Moon, Sun } from 'lucide-react';
import type { HTMLAttributes } from 'react';

type AppearanceTab = {
    value: Appearance;
    icon: LucideIcon;
    label_path: string;
};

export default function AppearanceToggleTab({ className = '', ...props }: Readonly<HTMLAttributes<HTMLDivElement>>) {
    const { __ } = lang();

    const { appearance, updateAppearance } = useAppearance();

    const tabs: AppearanceTab[] = [
        { value: 'light', icon: Sun, label_path: 'main.appearance_dropdown.light' },
        { value: 'dark', icon: Moon, label_path: 'main.appearance_dropdown.dark' },
        { value: 'system', icon: Monitor, label_path: 'main.appearance_dropdown.system' },
    ];

    return (
        <div
            className={cn('inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', className)}
            {...props}
        >
            {tabs.map(({ value, icon: Icon, label_path }) => (
                <button
                    key={value}
                    onClick={() => updateAppearance(value)}
                    className={cn(
                        'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                        appearance === value
                            ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                            : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
                    )}
                >
                    <Icon aria-hidden className="-ml-1 size-4" />
                    <span className="ml-1.5 text-sm">{__(label_path)}</span>
                </button>
            ))}
        </div>
    );
}
