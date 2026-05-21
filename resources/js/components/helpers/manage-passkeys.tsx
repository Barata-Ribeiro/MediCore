import { destroy } from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyRegistrationController';
import Heading from '@/components/common/heading';
import PasskeyItem from '@/components/helpers/passkey/passkey-item';
import PasskeyRegistration from '@/components/helpers/passkey/passkey-register';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';

import type { Passkey } from '@/types/auth';
import { router } from '@inertiajs/react';
import { KeyRound } from 'lucide-react';

export type Props = {
    canManagePasskeys?: boolean;
    passkeys?: Passkey[];
};

const EmptyState = () => {
    return (
        <Empty>
            <EmptyHeader>
                <EmptyMedia variant="icon">
                    <KeyRound aria-hidden className="size-7 text-muted-foreground" />
                </EmptyMedia>
                <EmptyTitle>No Passkeys</EmptyTitle>
                <EmptyDescription>Add a passkey to sign in without a password</EmptyDescription>
            </EmptyHeader>
        </Empty>
    );
};

export default function ManagePasskeys(props: Readonly<Props>) {
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
            <Heading variant="small" title="Passkeys" description="Manage your passkeys for passwordless sign-in" />

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
