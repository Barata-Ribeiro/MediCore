import { DataTableSelect } from '@/components/table/data-table-select';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTitle, PopoverTrigger } from '@/components/ui/popover';
import { useTableContext } from '@/hooks/table-context';
import { getFilterOperators, isActiveFilter } from '@/lib/data-table';
import type { ExtendedColumnFilter, FilterOperator } from '@/types/data-table';
import { lang } from '@erag/lang-sync-inertia/react';
import { FilterIcon, PlusIcon, XIcon } from 'lucide-react';
import { useState } from 'react';

export default function DataTableFilterList() {
    const table = useTableContext();
    const { __ } = lang();
    const [open, setOpen] = useState(false);
    const [draft, setDraft] = useState<ExtendedColumnFilter[]>([]);
    const columns = table
        .getAllLeafColumns()
        .filter((column) => column.getCanFilter() && column.columnDef.meta?.variant);
    const label = (key: string) => __(`main.data_table.filters.${key}`);
    const update = (index: number, changes: Partial<ExtendedColumnFilter>) =>
        setDraft((current) => current.map((filter, i) => (i === index ? { ...filter, ...changes } : filter)));

    return (
        <Popover
            open={open}
            onOpenChange={(next) => {
                if (next) setDraft(table.state.columnFilters as ExtendedColumnFilter[]);
                setOpen(next);
            }}
        >
            <PopoverTrigger
                render={
                    <Button variant="outline">
                        <FilterIcon data-icon="inline-start" />
                        {label('title')} ({table.state.columnFilters.length})
                    </Button>
                }
            />
            <PopoverContent align="start" className="max-h-[70vh] w-[min(48rem,calc(100vw-2rem))] overflow-y-auto">
                <PopoverTitle>{label('title')}</PopoverTitle>
                {draft.length === 0 && <p className="text-muted-foreground">{label('empty')}</p>}
                {draft.length > 1 && (
                    <DataTableSelect
                        label={label('join')}
                        value={draft[0]?.joinOperator ?? 'and'}
                        options={[
                            { value: 'and', label: label('and') },
                            { value: 'or', label: label('or') },
                        ]}
                        onChange={(join) =>
                            setDraft((current) =>
                                current.map((filter) => ({ ...filter, joinOperator: join as 'and' | 'or' })),
                            )
                        }
                    />
                )}
                <FieldGroup>
                    {draft.map((filter, index) => {
                        const column = columns.find((item) => item.id === filter.id);
                        const variant = column?.columnDef.meta?.variant ?? 'text';
                        const options =
                            column?.columnDef.meta?.options ??
                            (variant === 'boolean'
                                ? [
                                      { value: '1', label: label('true') },
                                      { value: '0', label: label('false') },
                                  ]
                                : []);
                        const values = Array.isArray(filter.value) ? filter.value : [filter.value];
                        const noValue = filter.operator === 'isEmpty' || filter.operator === 'isNotEmpty';
                        const inputType =
                            variant === 'date' && filter.operator !== 'isRelativeToToday'
                                ? 'date'
                                : variant === 'number' || filter.operator === 'isRelativeToToday'
                                  ? 'number'
                                  : 'text';
                        return (
                            <Field key={filter.filterId}>
                                <div className="flex flex-wrap items-center gap-2">
                                    <DataTableSelect
                                        label={label('column')}
                                        value={filter.id}
                                        options={columns.map((item) => ({
                                            value: item.id,
                                            label: item.columnDef.meta?.label ?? item.id,
                                        }))}
                                        onChange={(id) =>
                                            update(index, {
                                                id,
                                                value: '',
                                                operator:
                                                    getFilterOperators(
                                                        columns.find((item) => item.id === id)?.columnDef.meta
                                                            ?.variant ?? 'text',
                                                        __,
                                                    )[0]?.value ?? 'includesString',
                                            })
                                        }
                                    />
                                    <DataTableSelect
                                        label={label('operator')}
                                        value={filter.operator}
                                        options={getFilterOperators(variant, __)}
                                        onChange={(operator) =>
                                            update(index, {
                                                operator: operator as FilterOperator,
                                                value: operator === 'inRange' ? ['', ''] : '',
                                            })
                                        }
                                    />
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        aria-label={label('remove')}
                                        onClick={() => setDraft((current) => current.filter((_, i) => i !== index))}
                                    >
                                        <XIcon />
                                    </Button>
                                </div>
                                {!noValue &&
                                    (options.length ? (
                                        <div className="flex flex-wrap gap-3">
                                            {options.map((option) => (
                                                <FieldLabel key={option.value}>
                                                    <Checkbox
                                                        checked={values.includes(option.value)}
                                                        onCheckedChange={(checked) =>
                                                            update(index, {
                                                                value: checked
                                                                    ? [...values.filter(Boolean), option.value]
                                                                    : values.filter((value) => value !== option.value),
                                                            })
                                                        }
                                                    />
                                                    {option.label}
                                                </FieldLabel>
                                            ))}
                                        </div>
                                    ) : (
                                        <div className="flex gap-2">
                                            <Input
                                                aria-label={
                                                    filter.operator === 'inRange' ? label('minimum') : label('value')
                                                }
                                                type={inputType}
                                                step={inputType === 'number' ? 'any' : undefined}
                                                value={values[0] ?? ''}
                                                onChange={(event) =>
                                                    update(index, {
                                                        value:
                                                            filter.operator === 'inRange'
                                                                ? [event.target.value, values[1] ?? '']
                                                                : event.target.value,
                                                    })
                                                }
                                            />
                                            {filter.operator === 'inRange' && (
                                                <Input
                                                    aria-label={label('maximum')}
                                                    type={inputType}
                                                    step={inputType === 'number' ? 'any' : undefined}
                                                    value={values[1] ?? ''}
                                                    onChange={(event) =>
                                                        update(index, { value: [values[0] ?? '', event.target.value] })
                                                    }
                                                />
                                            )}
                                        </div>
                                    ))}
                            </Field>
                        );
                    })}
                </FieldGroup>
                <div className="flex flex-wrap gap-2">
                    <Button
                        variant="outline"
                        disabled={!columns.length || draft.length >= 20}
                        onClick={() => {
                            const column = columns[0];
                            if (!column) return;
                            setDraft((current) => [
                                ...current,
                                {
                                    id: column.id,
                                    filterId: crypto.randomUUID(),
                                    operator:
                                        getFilterOperators(column.columnDef.meta?.variant ?? 'text', __)[0]?.value ??
                                        'includesString',
                                    value: '',
                                    joinOperator: current[0]?.joinOperator ?? 'and',
                                },
                            ]);
                        }}
                    >
                        <PlusIcon data-icon="inline-start" />
                        {label('add')}
                    </Button>
                    <Button variant="ghost" onClick={() => setDraft([])}>
                        {label('reset')}
                    </Button>
                    <Button
                        onClick={() => {
                            table.setColumnFilters(draft.filter(isActiveFilter));
                            setOpen(false);
                        }}
                    >
                        {label('apply')}
                    </Button>
                </div>
            </PopoverContent>
        </Popover>
    );
}
