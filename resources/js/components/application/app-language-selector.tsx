import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { update } from '@/routes/locale';
import type { AppLocale } from '@/types/auth';
import { router } from '@inertiajs/core';
import { usePage } from '@inertiajs/react';
import { LanguagesIcon } from 'lucide-react';
import { memo } from 'react';

const AppLanguageSelector = memo(() => {
    const { auth } = usePage().props;
    const currentLocale: AppLocale = auth.locale ?? 'en';

    const handleLocaleChange = (locale: string): void => {
        const nextLocale = locale as AppLocale;

        if (nextLocale === currentLocale) {
            return;
        }

        const endpoint = update();

        router.visit(endpoint.url, {
            method: endpoint.method,
            data: { locale: nextLocale },
            preserveScroll: true,
            preserveState: true,
        });
    };

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline">
                    <LanguagesIcon aria-hidden className="size-4" />
                    Languages
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className="w-full max-w-84">
                <DropdownMenuLabel>Select Language</DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuRadioGroup value={currentLocale} onValueChange={handleLocaleChange}>
                    <DropdownMenuRadioItem value="en">
                        <span className="flex items-center gap-2">
                            <span>🇺🇸</span>
                            <span>English</span>
                        </span>
                    </DropdownMenuRadioItem>
                    <DropdownMenuRadioItem value="pt_BR">
                        <span className="flex items-center gap-2">
                            <span>🇧🇷</span>
                            <span>Portuguese (Brazil)</span>
                        </span>
                    </DropdownMenuRadioItem>
                </DropdownMenuRadioGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
});

export default AppLanguageSelector;
