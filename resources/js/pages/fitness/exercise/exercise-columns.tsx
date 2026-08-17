import ActionConfirmationDialog from '@/components/common/action-confirmation-dialog';
import DataTableColumnHeader from '@/components/table/data-table-column-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { destroy, edit } from '@/routes/exercises';
import type { CatalogExercise } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon, ExternalLinkIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({ column, title }: Readonly<{ column: Column<CatalogExercise, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function ActionsCell({ exercise }: Readonly<{ exercise: CatalogExercise }>) {
    const { __ } = lang();
    const [open, setOpen] = useState(false);

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger
                    render={
                        <Button
                            aria-label={__('exercise_pages.index.table.menu.open_label')}
                            variant="ghost"
                            className="flex size-8 p-0 aria-expanded:bg-muted"
                        >
                            <EllipsisIcon aria-hidden size={16} />
                        </Button>
                    }
                />
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>{__('exercise_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem
                            render={
                                <Link className="block w-full" href={edit(exercise.id)} as="button">
                                    <EditIcon aria-hidden size={14} /> {__('exercise_pages.index.table.menu.edit')}
                                </Link>
                            }
                        />
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} /> {__('exercise_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('exercise_pages.index.table.delete_dialog.title')}
                description={__('exercise_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={destroy(exercise.id)}
            />
        </Fragment>
    );
}

export function useExerciseColumns(): ColumnDef<CatalogExercise>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('exercise_pages.index.table.columns.id')} />
            ),
            enableSorting: true,
            enableHiding: false,
            size: 40,
        },
        {
            accessorKey: 'name',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('exercise_pages.index.table.columns.name')} />
            ),
            cell: ({ row }) => (
                <div className="grid gap-1">
                    <div className="font-medium">{row.original.name}</div>
                    {row.original.description && (
                        <p className="truncate text-xs text-muted-foreground">{row.original.description}</p>
                    )}
                </div>
            ),
            enableSorting: true,
        },
        {
            accessorKey: 'muscle_group_name',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('exercise_pages.index.table.columns.muscle_groups')} />
            ),
            cell: ({ row }) => (
                <div className="flex flex-wrap gap-1">
                    {row.original.muscle_groups.map((muscleGroup) => (
                        <Badge key={muscleGroup.id} variant="outline">
                            {muscleGroup.name}
                        </Badge>
                    ))}
                </div>
            ),
            enableSorting: true,
        },
        {
            accessorKey: 'video_url',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('exercise_pages.index.table.columns.video')} />
            ),
            cell: ({ row }) =>
                row.original.video_url ? (
                    <a
                        href={row.original.video_url}
                        target="_blank"
                        rel="noreferrer"
                        className="inline-flex items-center gap-1 text-sm underline"
                    >
                        {__('exercise_pages.index.table.open_video')} <ExternalLinkIcon aria-hidden size={14} />
                    </a>
                ) : (
                    <span className="text-sm text-muted-foreground">-</span>
                ),
            meta: { label: __('exercise_pages.index.table.columns.video') },
            enableSorting: false,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('exercise_pages.index.table.columns.created_at')} />
            ),
            cell: ({ row }) => new Date(row.original.created_at).toLocaleDateString(),
            meta: {
                label: __('exercise_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell exercise={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
