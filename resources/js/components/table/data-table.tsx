import DataTableExportData from '@/components/table/data-table-export-data';
import DataTableToolbar from '@/components/table/data-table-toolbar';
import { Button } from '@/components/ui/button';
import { ButtonGroup } from '@/components/ui/button-group';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useAppTable } from '@/hooks/table';
import { parseTableState } from '@/lib/data-table';
import type { PaginationMeta } from '@/types/application/metadata';
import type { Column, ColumnDef, ExtendedColumnFilter } from '@/types/data-table';
import type { RouteDefinition } from '@/wayfinder';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link, router, usePage } from '@inertiajs/react';
import { ModalLink } from '@inertiaui/modal-react';
import type { ColumnFiltersState, PaginationState, RowData, SortingState, Updater } from '@tanstack/react-table';
import { ClipboardPlusIcon } from 'lucide-react';
import type { CSSProperties } from 'react';
import { useCallback, useRef, useState } from 'react';
import { toast } from 'sonner';

interface DataTableProps<TData extends { id: string | number }> {
    columns: ColumnDef<TData>[];
    data: TData[];
    pagination: Omit<PaginationMeta<TData[]>, 'data'>;
    createRoute?: RouteDefinition<'get'>;
    isModal?: boolean;
    exportables?: Partial<Record<'csvRoute' | 'pdfRoute', RouteDefinition<'get'>>>;
}

function pinningStyles<TData extends RowData>(column: Column<TData>): CSSProperties {
    const pinned = column.getIsPinned();
    return {
        insetInlineStart: pinned === 'start' ? column.getStart('start') : undefined,
        insetInlineEnd: pinned === 'end' ? column.getAfter('end') : undefined,
        position: pinned ? 'sticky' : 'relative',
        background: 'var(--background)',
        width: column.getSize(),
        zIndex: pinned ? 1 : undefined,
    };
}

function update<T>(updater: Updater<T>, previous: T): T {
    return typeof updater === 'function' ? (updater as (value: T) => T)(previous) : updater;
}

export function DataTable<TData extends { id: string | number }>({
    columns,
    data,
    pagination,
    createRoute,
    isModal = false,
    exportables,
}: Readonly<DataTableProps<TData>>) {
    const { __ } = lang();
    const page = usePage();

    const params = new URL(page.url, 'http://localhost').searchParams;
    const serverState = {
        sorting: parseTableState<SortingState>(params.get('sorting'), []),
        columnFilters: parseTableState<ExtendedColumnFilter[]>(params.get('filters'), []),
        globalFilter: params.get('search') ?? '',
        pagination: { pageIndex: Math.max(0, pagination.current_page - 1), pageSize: pagination.per_page },
    };

    const [state, setState] = useState(serverState);
    const [sourceUrl, setSourceUrl] = useState(page.url);
    const [busy, setBusy] = useState(false);
    const navigationVersion = useRef(0);

    if (sourceUrl !== page.url) {
        setSourceUrl(page.url);
        setState(serverState);
    }

    function navigate(next: typeof state) {
        const version = ++navigationVersion.current;
        setState(next);
        setBusy(true);

        router.get(
            pagination.path,
            {
                ...(next.sorting.length ? { sorting: JSON.stringify(next.sorting) } : {}),
                ...(next.columnFilters.length ? { filters: JSON.stringify(next.columnFilters) } : {}),
                ...(next.globalFilter ? { search: next.globalFilter } : {}),
                per_page: next.pagination.pageSize,
                page: next.pagination.pageIndex + 1,
            },
            {
                prefetch: true,
                preserveState: true,
                preserveScroll: true,
                onError: (errors) => {
                    if (version !== navigationVersion.current) return;
                    setState(serverState);
                    toast.error(Object.values(errors)[0] ?? __('main.data_table.toolbar.search.flash_error'));
                },
                onFinish: () => {
                    if (version === navigationVersion.current) setBusy(false);
                },
            },
        );
    }

    const reset = useCallback(() => {
        navigate({
            ...state,
            sorting: [],
            columnFilters: [],
            globalFilter: '',
            pagination: { ...state.pagination, pageIndex: 0 },
        });
    }, [navigate, state]);

    const table = useAppTable({
        columns,
        data,
        rowCount: pagination.total,
        getRowId: (row) => String(row.id),
        initialState: { columnPinning: { start: ['id'], end: ['actions'] } },
        state,
        onSortingChange: (updater: Updater<SortingState>) =>
            navigate({
                ...state,
                sorting: update(updater, state.sorting),
                pagination: { ...state.pagination, pageIndex: 0 },
            }),
        onColumnFiltersChange: (updater: Updater<ColumnFiltersState>) =>
            navigate({
                ...state,
                columnFilters: update(updater, state.columnFilters) as ExtendedColumnFilter[],
                pagination: { ...state.pagination, pageIndex: 0 },
            }),
        onGlobalFilterChange: (updater: Updater<string>) =>
            navigate({
                ...state,
                globalFilter: update(updater, state.globalFilter),
                pagination: { ...state.pagination, pageIndex: 0 },
            }),
        onPaginationChange: (updater: Updater<PaginationState>) => {
            const next = update(updater, state.pagination);
            navigate({
                ...state,
                pagination: { ...next, pageIndex: next.pageSize === state.pagination.pageSize ? next.pageIndex : 0 },
            });
        },
    });

    return (
        <table.AppTable>
            <Card className="mx-auto w-full" aria-busy={busy}>
                <CardHeader className="flex flex-wrap items-center justify-between gap-4">
                    <fieldset disabled={busy} className="contents">
                        <DataTableToolbar onReset={reset} />
                    </fieldset>

                    <ButtonGroup>
                        {createRoute && (
                            <Button
                                render={
                                    isModal ? (
                                        <ModalLink
                                            href={createRoute.url}
                                            method={createRoute.method}
                                            as="button"
                                            prefetch
                                        >
                                            <ClipboardPlusIcon aria-hidden data-icon="inline-start" />
                                            {__('main.data_table.create_record.action')}
                                        </ModalLink>
                                    ) : (
                                        <Link href={createRoute} as="button" prefetch>
                                            <ClipboardPlusIcon aria-hidden data-icon="inline-start" />
                                            {__('main.data_table.create_record.action')}
                                        </Link>
                                    )
                                }
                            />
                        )}
                        {exportables && <DataTableExportData csv={exportables.csvRoute} pdf={exportables.pdfRoute} />}
                    </ButtonGroup>
                </CardHeader>

                <CardContent className="border-y py-4">
                    <Table>
                        <TableHeader>
                            {table.getHeaderGroups().map((group) => (
                                <TableRow key={group.id}>
                                    {group.headers.map((header) => (
                                        <TableHead
                                            key={header.id}
                                            colSpan={header.colSpan}
                                            style={pinningStyles(header.column)}
                                        >
                                            {!header.isPlaceholder && (
                                                <table.AppHeader header={header}>
                                                    {(context) => <context.FlexRender />}
                                                </table.AppHeader>
                                            )}
                                        </TableHead>
                                    ))}
                                </TableRow>
                            ))}
                        </TableHeader>
                        <TableBody>
                            {table.getRowModel().rows.length ? (
                                table.getRowModel().rows.map((row) => (
                                    <TableRow key={row.id}>
                                        {row.getVisibleCells().map((cell) => (
                                            <TableCell key={cell.id} style={pinningStyles(cell.column)}>
                                                <table.AppCell cell={cell}>
                                                    {(context) => <context.FlexRender />}
                                                </table.AppCell>
                                            </TableCell>
                                        ))}
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell
                                        colSpan={table.getVisibleLeafColumns().length}
                                        className="h-24 text-center"
                                    >
                                        {__('main.data_table.empty_message')}
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </CardContent>
                <CardFooter>
                    <fieldset disabled={busy} className="w-full">
                        <table.Pagination />
                    </fieldset>
                </CardFooter>
            </Card>
        </table.AppTable>
    );
}
