import LipidProfileController from '@/actions/App/Http/Controllers/Exams/LipidProfileController';
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
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

export const columns: ColumnDef<LipidProfile>[] = [
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
        accessorKey: 'total_cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Total Cholesterol" />,
        cell: ({ row }) => `${row.original.total_cholesterol} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'hdl_cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="HDL Cholesterol" />,
        cell: ({ row }) => `${row.original.hdl_cholesterol} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'ldl_cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="LDL Cholesterol" />,
        cell: ({ row }) => `${row.original.ldl_cholesterol} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'vldl_cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="VLDL Cholesterol" />,
        cell: ({ row }) => `${row.original.vldl_cholesterol} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'triglycerides',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Triglycerides" />,
        cell: ({ row }) => `${row.original.triglycerides} mg/dL`,
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

            const valuesToCopy = `Report Date: ${format(row.original.report_date, 'PPP')}, Total Cholesterol: ${row.original.total_cholesterol} mg/dL, HDL Cholesterol: ${row.original.hdl_cholesterol} mg/dL, LDL Cholesterol: ${row.original.ldl_cholesterol} mg/dL, VLDL Cholesterol: ${row.original.vldl_cholesterol} mg/dL, Triglycerides: ${row.original.triglycerides} mg/dL, Created At: ${format(row.original.created_at, 'PPpp')}`;

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
                                    <Link
                                        className="block w-full"
                                        href={LipidProfileController.edit(row.original.id)}
                                        as="button"
                                    >
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
                        route={LipidProfileController.destroy(row.original.id)}
                    />
                </Fragment>
            );
        },
        size: 40,
        enableHiding: false,
    },
];
