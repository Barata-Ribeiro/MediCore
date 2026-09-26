import { download } from '@/actions/App/Http/Controllers/Exams/ExamCsvController';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { ExamCsvTransfer } from '@/types/exam-csv';
import { lang } from '@erag/lang-sync-inertia/react';
import { router, usePage } from '@inertiajs/react';
import { useEcho } from '@laravel/echo-react';
import { DownloadIcon } from 'lucide-react';
import { useEffect, useRef, useState, useSyncExternalStore } from 'react';
import { toast } from 'sonner';

function Transfers() {
    const { __ } = lang();
    const { auth, examCsvTransfers = [] } = usePage().props;
    const [received, setReceived] = useState<Record<string, ExamCsvTransfer>>({});
    const announced = useRef(new Set<string>());
    const { channel } = useEcho<ExamCsvTransfer>(`App.Models.User.${auth.user.id}`, 'ExamCsvFinished', (transfer) =>
        setReceived((previous) => ({ ...previous, [transfer.id]: transfer })),
    );

    useEffect(() => {
        const refresh = () => router.reload({ only: ['examCsvTransfers'] });
        const subscription = channel();
        subscription?.listen('.pusher:subscription_succeeded', refresh);
        window.addEventListener('focus', refresh);
        return () => {
            subscription?.stopListening('.pusher:subscription_succeeded', refresh);
            window.removeEventListener('focus', refresh);
        };
    }, [channel]);

    const transfers = Object.values({
        ...Object.fromEntries(examCsvTransfers.map((transfer) => [transfer.id, transfer])),
        ...received,
    })
        .filter((transfer) => Date.parse(transfer.expires_at) > Date.now())
        .sort((a, b) => b.id.localeCompare(a.id))
        .slice(0, 20);

    useEffect(() => {
        for (const transfer of transfers) {
            if (!['completed', 'failed'].includes(transfer.status) || announced.current.has(transfer.id)) continue;
            announced.current.add(transfer.id);
            const message = __(`exam_csv.${transfer.error_code ?? `${transfer.direction}_completed`}`, {
                exam: __(`exam_csv.exams.${transfer.exam_type}`),
                count: transfer.processed,
                line: transfer.error_line ?? '',
            });
            if (transfer.status === 'failed') {
                toast.error(message, { id: transfer.id });
            } else {
                toast.success(message, {
                    id: transfer.id,
                    duration: transfer.direction === 'export' ? 15000 : 5000,
                    action:
                        transfer.direction === 'export'
                            ? {
                                  label: __('exam_csv.download'),
                                  onClick: () => {
                                      window.location.href = download.url(transfer.id);
                                  },
                              }
                            : undefined,
                });
            }
        }
    }, [transfers, __]);

    return (
        <DropdownMenu>
            <DropdownMenuTrigger
                render={
                    <Button
                        variant="outline"
                        size="icon"
                        aria-label={__('exam_csv.transfers')}
                        title={__('exam_csv.transfers')}
                    >
                        <DownloadIcon aria-hidden />
                    </Button>
                }
            />
            <DropdownMenuContent align="end" className="w-80">
                <DropdownMenuGroup>
                    <DropdownMenuLabel>{__('exam_csv.transfers')}</DropdownMenuLabel>
                    <DropdownMenuLabel>{__('exam_csv.availability')}</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    {transfers.length === 0 && <DropdownMenuItem disabled>{__('exam_csv.empty')}</DropdownMenuItem>}
                    {transfers.map((transfer) => {
                        const ready = transfer.direction === 'export' && transfer.status === 'completed';
                        const label = `${__(`exam_csv.exams.${transfer.exam_type}`)} — ${__(`exam_csv.${ready ? 'download' : transfer.status}`)}`;
                        return ready ? (
                            <DropdownMenuItem key={transfer.id} render={<a href={download.url(transfer.id)} />}>
                                <DownloadIcon aria-hidden /> {label}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem key={transfer.id} disabled>
                                {label}
                            </DropdownMenuItem>
                        );
                    })}
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

const subscribeToMount = () => () => {};

export default function ExamCsvTransfers() {
    const mounted = useSyncExternalStore(
        subscribeToMount,
        () => true,
        () => false,
    );
    return mounted ? <Transfers /> : null;
}
