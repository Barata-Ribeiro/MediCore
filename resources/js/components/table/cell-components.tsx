import { useCellContext } from '@/hooks/table-context';
import { tz } from '@date-fns/tz';
import { usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { enUS, ptBR } from 'date-fns/locale';

export function TextCell() {
    const cell = useCellContext<string | number | boolean | null>();
    return <span>{String(cell.getValue() ?? '—')}</span>;
}

export function DateCell() {
    const cell = useCellContext<string>();
    const { auth } = usePage().props;
    const value = cell.getValue();

    const locale = auth.locale === 'pt_BR' ? ptBR : enUS;

    return (
        <time dateTime={value ?? undefined}>
            {value ? format(new Date(value), 'PPP', { locale, in: tz('UTC') }) : '—'}
        </time>
    );
}
