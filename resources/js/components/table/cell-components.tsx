import { useCellContext } from '@/hooks/table-context';
import { usePage } from '@inertiajs/react';

export function TextCell() {
    const cell = useCellContext<string | number | boolean | null>();
    return <span>{String(cell.getValue() ?? '—')}</span>;
}

export function DateCell() {
    const cell = useCellContext<string>();
    const { auth } = usePage().props;
    const value = cell.getValue();
    return (
        <span>
            {value
                ? new Intl.DateTimeFormat((auth.locale ?? 'en').replace('_', '-'), { timeZone: 'UTC' }).format(
                      new Date(value),
                  )
                : '—'}
        </span>
    );
}
