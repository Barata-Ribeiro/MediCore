import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Column } from '@/types/data-table';
import { lang } from '@erag/lang-sync-inertia/react';
import { Subscribe, type RowData } from '@tanstack/react-table';
import { ArrowDownIcon, ArrowUpIcon, ChevronsUpDownIcon, EyeOffIcon } from 'lucide-react';

export default function DataTableColumnHeader<TData extends RowData, TValue>({
    column,
    title,
}: Readonly<{ column: Column<TData, TValue>; title: string }>) {
    const { __ } = lang();
    if (!column.getCanSort() && !column.getCanHide()) return <span>{title}</span>;
    return (
        <Subscribe source={column.table.atoms.sorting}>
            {(sorting) => {
                const sort = sorting.find((item) => item.id === column.id);
                const Icon = sort ? (sort.desc ? ArrowDownIcon : ArrowUpIcon) : ChevronsUpDownIcon;
                return (
                    <DropdownMenu>
                        <DropdownMenuTrigger
                            render={
                                <Button variant="ghost" size="sm">
                                    {title}
                                    <Icon data-icon="inline-end" />
                                </Button>
                            }
                        />
                        <DropdownMenuContent align="start">
                            <DropdownMenuGroup>
                                {column.getCanSort() && (
                                    <>
                                        <DropdownMenuItem onClick={() => column.toggleSorting(false, true)}>
                                            <ArrowUpIcon />
                                            {__('main.data_table.column_header.asc')}
                                        </DropdownMenuItem>
                                        <DropdownMenuItem onClick={() => column.toggleSorting(true, true)}>
                                            <ArrowDownIcon />
                                            {__('main.data_table.column_header.desc')}
                                        </DropdownMenuItem>
                                        <DropdownMenuItem onClick={() => column.clearSorting()}>
                                            {__('main.data_table.column_header.clear')}
                                        </DropdownMenuItem>
                                    </>
                                )}
                                {column.getCanSort() && column.getCanHide() && <DropdownMenuSeparator />}
                                {column.getCanHide() && (
                                    <DropdownMenuItem onClick={() => column.toggleVisibility(false)}>
                                        <EyeOffIcon />
                                        {__('main.data_table.column_header.hide')}
                                    </DropdownMenuItem>
                                )}
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                );
            }}
        </Subscribe>
    );
}
