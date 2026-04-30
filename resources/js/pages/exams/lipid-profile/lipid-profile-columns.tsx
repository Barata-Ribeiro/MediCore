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
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({ column, title }: Readonly<{ column: Column<LipidProfile, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function LipidValueCell({ value }: Readonly<{ value: number }>) {
    const { __ } = lang();

    return `${value} ${__('lipid_profile_pages.shared.unit')}`;
}

function ActionsCell({ lipidProfile }: Readonly<{ lipidProfile: LipidProfile }>) {
    const { __, trans } = lang();
    const [open, setOpen] = useState(false);

    const valuesToCopy = trans('lipid_profile_pages.index.table.copy_values_content', {
        report_date: format(lipidProfile.report_date, 'PPP'),
        total_cholesterol: lipidProfile.total_cholesterol,
        hdl_cholesterol: lipidProfile.hdl_cholesterol,
        ldl_cholesterol: lipidProfile.ldl_cholesterol,
        vldl_cholesterol: lipidProfile.vldl_cholesterol,
        triglycerides: lipidProfile.triglycerides,
        unit: __('lipid_profile_pages.shared.unit'),
        created_at: format(lipidProfile.created_at, 'PPP p'),
    });

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <Button
                        aria-label={__('lipid_profile_pages.index.table.menu.open_label')}
                        variant="ghost"
                        className="flex size-8 p-0 data-[state=open]:bg-muted"
                    >
                        <EllipsisIcon aria-hidden size={16} />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>{__('lipid_profile_pages.index.table.menu.copy_fields')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <DropdownMenuCopyButton content={valuesToCopy}>
                                {__('lipid_profile_pages.index.table.menu.copy_values')}
                            </DropdownMenuCopyButton>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuLabel>{__('lipid_profile_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <Link
                                className="block w-full"
                                href={LipidProfileController.edit(lipidProfile.id)}
                                as="button"
                            >
                                <EditIcon aria-hidden size={14} /> {__('lipid_profile_pages.index.table.menu.edit')}
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} /> {__('lipid_profile_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('lipid_profile_pages.index.table.delete_dialog.title')}
                description={__('lipid_profile_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={LipidProfileController.destroy(lipidProfile.id)}
            />
        </Fragment>
    );
}

export function useLipidProfileColumns(): ColumnDef<LipidProfile>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('lipid_profile_pages.index.table.columns.id')} />
            ),
            enableSorting: true,
            enableHiding: false,
            size: 40,
        },
        {
            accessorKey: 'report_date',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('lipid_profile_pages.index.table.columns.report_date')} />
            ),
            cell: ({ row }) => format(row.original.report_date, 'PPP'),
            meta: {
                label: __('lipid_profile_pages.index.table.columns.report_date'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'total_cholesterol',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('lipid_profile_pages.index.table.columns.total_cholesterol')}
                />
            ),
            cell: ({ row }) => <LipidValueCell value={row.original.total_cholesterol} />,
            enableSorting: true,
        },
        {
            accessorKey: 'hdl_cholesterol',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('lipid_profile_pages.index.table.columns.hdl_cholesterol')}
                />
            ),
            cell: ({ row }) => <LipidValueCell value={row.original.hdl_cholesterol} />,
            enableSorting: true,
        },
        {
            accessorKey: 'ldl_cholesterol',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('lipid_profile_pages.index.table.columns.ldl_cholesterol')}
                />
            ),
            cell: ({ row }) => <LipidValueCell value={row.original.ldl_cholesterol} />,
            enableSorting: true,
        },
        {
            accessorKey: 'vldl_cholesterol',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('lipid_profile_pages.index.table.columns.vldl_cholesterol')}
                />
            ),
            cell: ({ row }) => <LipidValueCell value={row.original.vldl_cholesterol} />,
            enableSorting: true,
        },
        {
            accessorKey: 'triglycerides',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('lipid_profile_pages.index.table.columns.triglycerides')}
                />
            ),
            cell: ({ row }) => <LipidValueCell value={row.original.triglycerides} />,
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('lipid_profile_pages.index.table.columns.created_at')} />
            ),
            cell: ({ row }) => format(row.original.created_at, 'PPpp'),
            meta: {
                label: __('lipid_profile_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell lipidProfile={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
