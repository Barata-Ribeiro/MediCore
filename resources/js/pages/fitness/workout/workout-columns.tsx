import ActionConfirmationDialog from '@/components/common/action-confirmation-dialog';
import DataTableColumnHeader from '@/components/table/data-table-column-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { destroy, edit, show } from '@/routes/workouts';
import type { WorkoutSummary } from '@/types/application/fitness/workout';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { EllipsisIcon, EyeIcon, PencilIcon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

function ActionsCell({ workout }: Readonly<{ workout: WorkoutSummary }>) {
    const { __ } = lang();
    const [open, setOpen] = useState(false);

    return (
        <>
            <DropdownMenu>
                <DropdownMenuTrigger
                    render={<Button variant="ghost" size="icon" aria-label={__('workout_pages.index.table.actions')} />}
                >
                    <EllipsisIcon />
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuGroup>
                        <DropdownMenuItem render={<Link href={show(workout.id)} />}>
                            <EyeIcon />
                            {__('workout_pages.index.actions.view')}
                        </DropdownMenuItem>
                        <DropdownMenuItem render={<Link href={edit(workout.id)} />}>
                            <PencilIcon />
                            {__('workout_pages.index.actions.edit')}
                        </DropdownMenuItem>
                        <DropdownMenuItem variant="destructive" onClick={() => setOpen(true)}>
                            <Trash2Icon />
                            {__('workout_pages.index.actions.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>
            <ActionConfirmationDialog
                open={open}
                setOpen={setOpen}
                method="delete"
                route={destroy(workout.id)}
                title={__('workout_pages.index.delete_title')}
                description={__('workout_pages.index.delete_description')}
                cancelLabel={__('workout_pages.shared.cancel')}
                confirmLabel={__('workout_pages.index.actions.delete')}
            />
        </>
    );
}

export function useWorkoutColumns(): ColumnDef<WorkoutSummary>[] {
    const { __ } = lang();
    return [
        {
            accessorKey: 'id',
            header: ({ column }) => <DataTableColumnHeader column={column} title="#" />,
            enableHiding: false,
            size: 60,
        },
        {
            accessorKey: 'goal',
            header: ({ column }) => (
                <DataTableColumnHeader column={column} title={__('workout_pages.index.table.goal')} />
            ),
            meta: { label: __('workout_pages.index.table.goal') },
            cell: ({ row }) => (
                <Link href={show(row.original.id)} className="font-medium underline-offset-4 hover:underline" prefetch>
                    {row.original.goal || __('workout_pages.shared.untitled')}
                </Link>
            ),
        },
        ...(['method', 'sections_count', 'exercises_count'] as const).map<ColumnDef<WorkoutSummary>>((key) => ({
            accessorKey: key,
            header: ({ column }) => (
                <DataTableColumnHeader column={column} title={__('workout_pages.index.table.' + key)} />
            ),
            meta: { label: __('workout_pages.index.table.' + key) },
            cell: ({ row }) => row.original[key] ?? '—',
        })),
        {
            accessorKey: 'is_active',
            header: ({ column }) => (
                <DataTableColumnHeader column={column} title={__('workout_pages.index.table.status')} />
            ),
            meta: {
                label: __('workout_pages.index.table.status'),
                variant: 'select',
                options: [
                    { label: __('workout_pages.shared.active_status'), value: '1' },
                    { label: __('workout_pages.shared.inactive_status'), value: '0' },
                ],
            },
            cell: ({ row }) => (
                <Badge variant={row.original.is_active ? 'default' : 'secondary'}>
                    {__('workout_pages.shared.' + (row.original.is_active ? 'active_status' : 'inactive_status'))}
                </Badge>
            ),
        },
        { id: 'actions', cell: ({ row }) => <ActionsCell workout={row.original} />, enableHiding: false, size: 50 },
    ];
}
