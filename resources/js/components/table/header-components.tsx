import DataTableColumnHeader from '@/components/table/data-table-column-header';
import { useHeaderContext } from '@/hooks/table-context';

export function ColumnHeader({ title }: Readonly<{ title?: string }>) {
    const header = useHeaderContext();
    return (
        <DataTableColumnHeader
            column={header.column}
            title={title ?? header.column.columnDef.meta?.label ?? header.column.id}
        />
    );
}
