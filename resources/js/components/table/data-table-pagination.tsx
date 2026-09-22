import { DataTableSelect } from '@/components/table/data-table-select';
import { Button } from '@/components/ui/button';
import { useTableContext } from '@/hooks/table-context';
import { lang } from '@erag/lang-sync-inertia/react';
import { ChevronLeftIcon, ChevronRightIcon, ChevronsLeftIcon, ChevronsRightIcon } from 'lucide-react';

export default function DataTablePagination() {
    const table = useTableContext();
    const { __, trans } = lang();
    const { pageIndex, pageSize } = table.state.pagination;
    const label = (key: string) => __(`main.data_table.pagination.${key}`);
    return (
        <div className="flex w-full flex-wrap items-center justify-between gap-4">
            <span>{trans('main.data_table.pagination.summary', { total: table.options.rowCount ?? 0 })}</span>
            <div className="flex flex-wrap items-center gap-2">
                <span>{label('per_page')}</span>
                <DataTableSelect
                    label={label('per_page')}
                    value={String(pageSize)}
                    options={[5, 10, 25, 75].map((size) => ({ value: String(size), label: String(size) }))}
                    onChange={(value) => table.setPageSize(Number(value))}
                />
                <span>
                    {trans('main.data_table.pagination.page', {
                        current: pageIndex + 1,
                        total: Math.max(1, table.getPageCount()),
                    })}
                </span>
                <Button
                    variant="outline"
                    size="icon"
                    disabled={!table.getCanPreviousPage()}
                    aria-label={label('first')}
                    onClick={() => table.firstPage()}
                >
                    <ChevronsLeftIcon />
                </Button>
                <Button
                    variant="outline"
                    size="icon"
                    disabled={!table.getCanPreviousPage()}
                    aria-label={label('previous')}
                    onClick={() => table.previousPage()}
                >
                    <ChevronLeftIcon />
                </Button>
                <Button
                    variant="outline"
                    size="icon"
                    disabled={!table.getCanNextPage()}
                    aria-label={label('next')}
                    onClick={() => table.nextPage()}
                >
                    <ChevronRightIcon />
                </Button>
                <Button
                    variant="outline"
                    size="icon"
                    disabled={!table.getCanNextPage()}
                    aria-label={label('last')}
                    onClick={() => table.lastPage()}
                >
                    <ChevronsRightIcon />
                </Button>
            </div>
        </div>
    );
}
