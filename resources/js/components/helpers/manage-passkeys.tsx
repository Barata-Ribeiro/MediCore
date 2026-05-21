import { destroy } from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyRegistrationController';
import Heading from '@/components/common/heading';
import PasskeyItem from '@/components/helpers/passkey/passkey-item';
import PasskeyRegistration from '@/components/helpers/passkey/passkey-register';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';

import type { Passkey } from '@/types/auth';
import { lang } from '@erag/lang-sync-inertia/react';
import { router } from '@inertiajs/react';
import { KeyRound } from 'lucide-react';

export type Props = {
    canManagePasskeys?: boolean;
    passkeys?: Passkey[];
};

const EmptyState = () => {
    const { __ } = lang();

    return (
        <Empty>
            <EmptyHeader>
                <EmptyMedia variant="icon">
                    <KeyRound aria-hidden className="size-7 text-muted-foreground" />
                </EmptyMedia>
                <EmptyTitle>{__('settings_pages.security_page.passkeys_section.empty_title')}</EmptyTitle>
                <EmptyDescription>
                    {__('settings_pages.security_page.passkeys_section.empty_description')}
                </EmptyDescription>
            </EmptyHeader>
        </Empty>
    );
};

export default function ManagePasskeys(props: Readonly<Props>) {
    const { __ } = lang();
    const passkeys = props.passkeys ?? [];

    const handleDelete = (id: number, onError: () => void) => {
        router.delete(destroy.url(id), {
            preserveScroll: true,
            onError,
        });
    };

    const handleRegisterSuccess = () => {
        router.reload();
    };

    if (!(props.canManagePasskeys ?? false)) {
        return null;
    }

    return (
        <div className="space-y-6">
            <Heading
                variant="small"
                title={__('settings_pages.security_page.passkeys_section.title')}
                description={__('settings_pages.security_page.passkeys_section.description')}
            />

            <div className="overflow-hidden rounded-lg border border-border">
                {passkeys.length > 0 ? (
                    passkeys.map((passkey) => (
                        <PasskeyItem key={passkey.id} passkey={passkey} onDelete={handleDelete} />
                    ))
                ) : (
                    <EmptyState />
                )}
            </div>

            <PasskeyRegistration onSuccess={handleRegisterSuccess} />
        </div>
    );
}
