import { DataTableSelect } from '@/components/table/data-table-select';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTitle, PopoverTrigger } from '@/components/ui/popover';
import { useTableContext } from '@/hooks/table-context';
import { lang } from '@erag/lang-sync-inertia/react';
import { ArrowDownIcon, ArrowUpIcon, ArrowUpDownIcon, PlusIcon, XIcon } from 'lucide-react';

export default function DataTableSortList() {
    const table = useTableContext();
    const { __ } = lang();
    const sorting = table.state.sorting;
    const columns = table.getAllLeafColumns().filter((column) => column.getCanSort());
    const label = (key: string) => __(`main.data_table.sorting.${key}`);
    const available = columns.filter((column) => !sorting.some((sort) => sort.id === column.id));
    function move(index: number, offset: number) {
        const next = [...sorting];
        const current = next[index];
        const target = next[index + offset];
        if (!current || !target) return;
        next[index] = target;
        next[index + offset] = current;
        table.setSorting(next);
    }
    return (
        <Popover>
            <PopoverTrigger
                render={
                    <Button variant="outline">
                        <ArrowUpDownIcon data-icon="inline-start" />
                        {label('title')} ({sorting.length})
                    </Button>
                }
            />
            <PopoverContent align="start" className="w-[min(32rem,calc(100vw-2rem))]">
                <PopoverTitle>{label('title')}</PopoverTitle>
                {sorting.length === 0 && <p className="text-muted-foreground">{label('empty')}</p>}
                {sorting.map((sort, index) => (
                    <div key={sort.id} className="flex flex-wrap items-center gap-2">
                        <DataTableSelect
                            label={label('column')}
                            value={sort.id}
                            options={columns
                                .filter((column) => column.id === sort.id || available.includes(column))
                                .map((column) => ({
                                    value: column.id,
                                    label: column.columnDef.meta?.label ?? column.id,
                                }))}
                            onChange={(id) =>
                                table.setSorting(sorting.map((item, i) => (i === index ? { ...item, id } : item)))
                            }
                        />
                        <DataTableSelect
                            label={label('direction')}
                            value={sort.desc ? 'desc' : 'asc'}
                            options={['asc', 'desc'].map((value) => ({
                                value,
                                label: __(`main.data_table.column_header.${value}`),
                            }))}
                            onChange={(direction) =>
                                table.setSorting(
                                    sorting.map((item, i) =>
                                        i === index ? { ...item, desc: direction === 'desc' } : item,
                                    ),
                                )
                            }
                        />
                        <Button
                            size="icon"
                            variant="ghost"
                            disabled={index === 0}
                            aria-label={label('up')}
                            onClick={() => move(index, -1)}
                        >
                            <ArrowUpIcon />
                        </Button>
                        <Button
                            size="icon"
                            variant="ghost"
                            disabled={index === sorting.length - 1}
                            aria-label={label('down')}
                            onClick={() => move(index, 1)}
                        >
                            <ArrowDownIcon />
                        </Button>
                        <Button
                            size="icon"
                            variant="ghost"
                            aria-label={label('remove')}
                            onClick={() => table.setSorting(sorting.filter((_, i) => i !== index))}
                        >
                            <XIcon />
                        </Button>
                    </div>
                ))}
                <div className="flex gap-2">
                    <Button
                        variant="outline"
                        disabled={!available.length || sorting.length >= 10}
                        onClick={() => {
                            const column = available[0];
                            if (column) table.setSorting([...sorting, { id: column.id, desc: false }]);
                        }}
                    >
                        <PlusIcon data-icon="inline-start" />
                        {label('add')}
                    </Button>
                    <Button variant="ghost" onClick={() => table.setSorting([])}>
                        {label('reset')}
                    </Button>
                </div>
            </PopoverContent>
        </Popover>
    );
}
