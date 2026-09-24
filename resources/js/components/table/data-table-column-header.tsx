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
import { ArrowDownIcon, ArrowUpIcon, ChevronsUpDownIcon, EyeOffIcon, XIcon } from 'lucide-react';
import { Activity } from 'react';

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
                const sortDirectionIcon = sort?.desc ? ArrowDownIcon : ArrowUpIcon;

                const Icon = sort ? sortDirectionIcon : ChevronsUpDownIcon;

                return (
                    <DropdownMenu>
                        <DropdownMenuTrigger
                            render={
                                <Button variant="ghost" size="sm">
                                    {title}
                                    <Icon aria-hidden data-icon="inline-end" />
                                </Button>
                            }
                        />
                        <DropdownMenuContent align="start">
                            <DropdownMenuGroup>
                                <Activity mode={column.getCanSort() ? 'visible' : 'hidden'}>
                                    <DropdownMenuItem onClick={() => column.toggleSorting(false, true)}>
                                        <ArrowUpIcon aria-hidden />
                                        {__('main.data_table.column_header.asc')}
                                    </DropdownMenuItem>
                                    <DropdownMenuItem onClick={() => column.toggleSorting(true, true)}>
                                        <ArrowDownIcon aria-hidden />
                                        {__('main.data_table.column_header.desc')}
                                    </DropdownMenuItem>
                                    <DropdownMenuItem onClick={() => column.clearSorting()}>
                                        <XIcon aria-hidden />
                                        {__('main.data_table.column_header.clear')}
                                    </DropdownMenuItem>
                                </Activity>

                                <Activity mode={column.getCanSort() && column.getCanHide() ? 'visible' : 'hidden'}>
                                    <DropdownMenuSeparator />
                                </Activity>

                                <Activity mode={column.getCanHide() ? 'visible' : 'hidden'}>
                                    <DropdownMenuItem onClick={() => column.toggleVisibility(false)}>
                                        <EyeOffIcon aria-hidden />
                                        {__('main.data_table.column_header.hide')}
                                    </DropdownMenuItem>
                                </Activity>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                );
            }}
        </Subscribe>
    );
}
