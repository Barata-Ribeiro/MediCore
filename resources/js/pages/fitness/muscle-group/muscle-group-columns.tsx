import ActionConfirmationDialog from '@/components/common/action-confirmation-dialog';
import DataTableColumnHeader from '@/components/table/data-table-column-header';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { destroy, edit } from '@/routes/muscle-groups';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { ModalLink } from '@inertiaui/modal-react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { CalendarIcon, DeleteIcon, DumbbellIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({
    column,
    title,
}: Readonly<{ column: Column<CatalogMuscleGroup, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function ActionsCell({ muscleGroup }: Readonly<{ muscleGroup: CatalogMuscleGroup }>) {
    const { __ } = lang();
    const [open, setOpen] = useState(false);

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger
                    render={
                        <Button
                            aria-label={__('muscle_group_pages.index.table.menu.open_label')}
                            variant="ghost"
                            className="flex size-8 p-0 aria-expanded:bg-muted"
                        >
                            <EllipsisIcon aria-hidden size={16} />
                        </Button>
                    }
                />
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>{__('muscle_group_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem
                            render={
                                <ModalLink
                                    className="block w-full"
                                    href={edit(muscleGroup.id).url}
                                    method={edit(muscleGroup.id).method}
                                    as="button"
                                >
                                    <EditIcon aria-hidden size={14} /> {__('muscle_group_pages.index.table.menu.edit')}
                                </ModalLink>
                            }
                        />
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} /> {__('muscle_group_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('muscle_group_pages.index.table.delete_dialog.title')}
                description={__('muscle_group_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={destroy(muscleGroup.id)}
            />
        </Fragment>
    );
}

export function useMuscleGroupColumns(): ColumnDef<CatalogMuscleGroup>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'name',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('muscle_group_pages.index.table.columns.name')} />
            ),
            cell: ({ row }) => <div className="font-medium">{row.original.name}</div>,
            enableSorting: true,
            enableHiding: false,
        },
        {
            accessorKey: 'exercises_count',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('muscle_group_pages.index.table.columns.exercises')} />
            ),
            cell: ({ row }) => row.original.exercises_count ?? 0,
            meta: {
                label: __('muscle_group_pages.index.table.columns.exercises'),
                variant: 'range',
                icon: DumbbellIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('muscle_group_pages.index.table.columns.created_at')} />
            ),
            cell: ({ row }) => new Date(row.original.created_at).toLocaleDateString(),
            meta: {
                label: __('muscle_group_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell muscleGroup={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
