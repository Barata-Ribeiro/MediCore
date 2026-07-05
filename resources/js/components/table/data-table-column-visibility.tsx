import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { lang } from '@erag/lang-sync-inertia/react';
import type { Table } from '@tanstack/react-table';
import { Settings2 } from 'lucide-react';

export default function DataTableColumnVisibility<TData>({
    table,
}: Readonly<{
    table: Table<TData>;
}>) {
    const { __ } = lang();

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline">
                    <Settings2 aria-hidden />
                    {__('main.data_table.column_visibility.action')}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-full">
                <DropdownMenuLabel>{__('main.data_table.column_visibility.label')}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {table
                    .getAllColumns()
                    .filter((column) => column.accessorFn !== undefined && column.getCanHide())
                    .map((column) => (
                        <DropdownMenuCheckboxItem
                            key={column.id}
                            checked={column.getIsVisible()}
                            onCheckedChange={(value) => column.toggleVisibility(value === true)}
                        >
                            {column.columnDef.meta?.label}
                        </DropdownMenuCheckboxItem>
                    ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
