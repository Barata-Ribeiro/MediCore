import { DataTableSelect } from '@/components/table/data-table-select';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTitle, PopoverTrigger } from '@/components/ui/popover';
import { useTableContext } from '@/hooks/table-context';
import { lang } from '@erag/lang-sync-inertia/react';
import { ArrowDownIcon, ArrowUpDownIcon, ArrowUpIcon, PlusIcon, XIcon } from 'lucide-react';
import { Activity } from 'react';

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
                        <ArrowUpDownIcon aria-hidden data-icon="inline-start" />
                        {label('title')} ({sorting.length})
                    </Button>
                }
            />
            <PopoverContent align="start" className="w-table-sort-popover">
                <PopoverTitle>{label('title')}</PopoverTitle>

                <Activity mode={sorting.length === 0 ? 'visible' : 'hidden'}>
                    <p className="text-muted-foreground">{label('empty')}</p>
                </Activity>

                <Activity mode={sorting.length > 0 ? 'visible' : 'hidden'}>
                    <div className="divide-y divide-dashed divide-border">
                        {sorting.map((sort, index) => (
                            <div key={sort.id} className="flex flex-wrap items-center gap-2 py-2 first:pt-0 last:pb-0">
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
                                        table.setSorting(
                                            sorting.map((item, i) => (i === index ? { ...item, id } : item)),
                                        )
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
                                    title={label('up')}
                                    onClick={() => move(index, -1)}
                                >
                                    <ArrowUpIcon aria-hidden />
                                </Button>

                                <Button
                                    size="icon"
                                    variant="ghost"
                                    disabled={index === sorting.length - 1}
                                    aria-label={label('down')}
                                    title={label('down')}
                                    onClick={() => move(index, 1)}
                                >
                                    <ArrowDownIcon aria-hidden />
                                </Button>

                                <Button
                                    size="icon"
                                    variant="ghost"
                                    aria-label={label('remove')}
                                    title={label('remove')}
                                    onClick={() => table.setSorting(sorting.filter((_, i) => i !== index))}
                                    className="ml-auto"
                                >
                                    <XIcon aria-hidden />
                                </Button>
                            </div>
                        ))}
                    </div>
                </Activity>

                <div className="flex gap-2">
                    <Button
                        variant="outline"
                        disabled={!available.length || sorting.length >= 10}
                        onClick={() => {
                            const column = available[0];
                            if (column) table.setSorting([...sorting, { id: column.id, desc: false }]);
                        }}
                    >
                        <PlusIcon aria-hidden data-icon="inline-start" />
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
