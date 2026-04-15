import ActionConfirmationDialog from '@/components/common/action-confirmation-dialog';
import DropdownMenuCopyButton from '@/components/common/dropdown-menu-copy-button';
import DataTableColumnHeader from '@/components/table/data-table-column-header';
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
import { destroy, edit } from '@/routes/urea-and-creatinine';
import type { UreaAndCreatinine } from '@/types/application/exams/urea-and-creatinine';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns/format';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

export const columns: ColumnDef<UreaAndCreatinine>[] = [
    {
        accessorKey: 'id',
        header: ({ column }) => <DataTableColumnHeader column={column} title="ID" />,
        enableSorting: true,
        enableHiding: false,
        size: 40,
    },
    {
        accessorKey: 'report_date',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Report Date" />,
        cell: ({ row }) => format(row.original.report_date, 'PPP'),
        meta: {
            label: 'Report Date',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },
    {
        accessorKey: 'urea_level',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Urea" />,
        cell: ({ row }) => `${row.original.urea_level} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'creatinine_level',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Creatinine" />,
        cell: ({ row }) => `${row.original.creatinine_level} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Created At" />,
        cell: ({ row }) => format(row.original.created_at, 'PPpp'),
        meta: {
            label: 'Created At',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },
    {
        id: 'actions',
        cell: function Cell({ row }) {
            const [open, setOpen] = useState(false);

            const valuesToCopy = `Report Date: ${format(row.original.report_date, 'PPP')}, Urea: ${row.original.urea_level} mg/dL, Creatinine: ${row.original.creatinine_level} mg/dL, Created At: ${format(row.original.created_at, 'PPP p')}`;

            return (
                <Fragment>
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild>
                            <Button
                                aria-label="Open menu"
                                variant="ghost"
                                className="flex size-8 p-0 data-[state=open]:bg-muted"
                            >
                                <EllipsisIcon aria-hidden size={16} />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" className="w-40">
                            <DropdownMenuLabel>Copy Fields</DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild>
                                    <DropdownMenuCopyButton content={valuesToCopy}>Copy Values</DropdownMenuCopyButton>
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                            <DropdownMenuSeparator />
                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild>
                                    <Link className="block w-full" href={edit(row.original.id)} as="button">
                                        <EditIcon aria-hidden size={14} /> Edit
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                                    <DeleteIcon aria-hidden size={14} /> Delete
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <ActionConfirmationDialog
                        title="Delete Record"
                        description="Are you sure you want to delete this record? This action cannot be undone."
                        open={open}
                        setOpen={setOpen}
                        method={'delete'}
                        route={destroy(row.original.id)}
                    />
                </Fragment>
            );
        },
        size: 40,
        enableHiding: false,
    },
];
