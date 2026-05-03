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
import { lang } from '@erag/lang-sync-inertia/react';
import { router } from '@inertiajs/core';
import { usePage } from '@inertiajs/react';
import { LanguagesIcon } from 'lucide-react';

export default function AppLanguageSelector() {
    const { __ } = lang();

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
            fresh: true,
            onSuccess: () => {
                router.flushAll();
            },
            onError: () => {
                router.flushAll();
            },
            preserveScroll: true,
            preserveState: false,
        });
    };

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline" className="w-full justify-start">
                    <LanguagesIcon aria-hidden className="size-4" />
                    <span>{currentLocale === 'en' ? '🇺🇸 English' : '🇧🇷 Português (Brasil)'}</span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
                <DropdownMenuLabel>{__('settings_pages.profile_page.app_language.selector_label')}</DropdownMenuLabel>
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
                            <span>Português (Brasil)</span>
                        </span>
                    </DropdownMenuRadioItem>
                </DropdownMenuRadioGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
