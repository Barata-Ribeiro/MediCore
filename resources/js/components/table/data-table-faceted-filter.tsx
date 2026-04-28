import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
} from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { lang } from '@erag/lang-sync-inertia/react';
import type { Column } from '@tanstack/react-table';
import { Check, PlusCircle, XCircle } from 'lucide-react';
import type { KeyboardEvent, MouseEvent } from 'react';
import { Activity, Fragment, useCallback, useMemo, useState } from 'react';

export interface Option {
    label: string;
    value: string;
}

interface DataTableFacetedFilterProps<TData, TValue> {
    column?: Column<TData, TValue>;
    title?: string;
    options: Option[];
    multiple?: boolean;
}

export function DataTableFacetedFilter<TData, TValue>({
    column,
    title,
    options,
    multiple,
}: Readonly<DataTableFacetedFilterProps<TData, TValue>>) {
    const [open, setOpen] = useState(false);
    const { __, trans } = lang();

    const columnFilterValue = column?.getFilterValue();

    const selectedValues = useMemo(() => {
        return new Set(Array.isArray(columnFilterValue) ? columnFilterValue : []);
    }, [columnFilterValue]);

    const onItemSelect = useCallback(
        (option: Option, isSelected: boolean) => {
            if (!column) {
                return;
            }

            if (multiple) {
                const newSelectedValues = new Set(selectedValues);

                if (isSelected) {
                    newSelectedValues.delete(option.value);
                } else {
                    newSelectedValues.add(option.value);
                }

                const filterValues = Array.from(newSelectedValues);
                column.setFilterValue(filterValues.length ? filterValues : undefined);
            } else {
                column.setFilterValue(isSelected ? undefined : [option.value]);
                setOpen(false);
            }
        },
        [column, multiple, selectedValues],
    );

    const onReset = useCallback(
        (event?: MouseEvent | KeyboardEvent<HTMLDivElement>) => {
            event?.stopPropagation();
            column?.setFilterValue(undefined);
        },
        [column],
    );

    const onResetKeyDown = useCallback(
        (event: KeyboardEvent<HTMLDivElement>) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                onReset(event);
            }
        },
        [onReset],
    );

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
                <Button variant="outline">
                    {selectedValues?.size > 0 ? (
                        <div
                            role="button"
                            aria-label={trans('main.data_table.toolbar.faceted_filter.clear_titled_action', {
                                title: title ?? 'unknown',
                            })}
                            tabIndex={0}
                            className="rounded-sm opacity-70 transition-opacity hover:opacity-100 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                            onClick={onReset}
                            onKeyDown={onResetKeyDown}
                        >
                            <XCircle aria-hidden />
                        </div>
                    ) : (
                        <PlusCircle aria-hidden />
                    )}
                    {title}
                    {selectedValues?.size > 0 && (
                        <Fragment>
                            <Separator orientation="vertical" className="mx-0.5 data-[orientation=vertical]:h-4" />
                            <Badge variant="secondary" className="rounded-sm px-1 font-normal lg:hidden">
                                {selectedValues.size}
                            </Badge>
                            <div className="hidden items-center gap-1 lg:flex">
                                {selectedValues.size > 2 ? (
                                    <Badge variant="secondary" className="rounded-sm px-1 font-normal">
                                        {selectedValues.size}{' '}
                                        {__('main.data_table.toolbar.faceted_filter.badge_selected_suffix')}
                                    </Badge>
                                ) : (
                                    options
                                        .filter((option) => selectedValues.has(option.value))
                                        .map((option) => (
                                            <Badge
                                                variant="secondary"
                                                key={option.value}
                                                className="rounded-sm px-1 font-normal"
                                            >
                                                {option.label}
                                            </Badge>
                                        ))
                                )}
                            </div>
                        </Fragment>
                    )}
                </Button>
            </PopoverTrigger>
            <PopoverContent className="w-50 p-0" align="start">
                <Command>
                    <CommandInput placeholder={title} />
                    <CommandList className="max-h-full">
                        <CommandEmpty>{__('main.data_table.toolbar.faceted_filter.empty_results')}</CommandEmpty>
                        <CommandGroup className="max-h-75 scroll-py-1 overflow-x-hidden overflow-y-auto">
                            {options.map((option) => {
                                const isSelected = selectedValues.has(option.value);

                                return (
                                    <CommandItem key={option.value} onSelect={() => onItemSelect(option, isSelected)}>
                                        <div
                                            className={cn(
                                                'flex size-4 items-center justify-center rounded-sm border border-secondary',
                                                isSelected ? 'bg-secondary' : 'opacity-50 [&_svg]:invisible',
                                            )}
                                        >
                                            <Check aria-hidden />
                                        </div>
                                        <span className="truncate">{option.label}</span>
                                    </CommandItem>
                                );
                            })}
                        </CommandGroup>

                        <Activity mode={selectedValues.size > 0 ? 'visible' : 'hidden'}>
                            <CommandSeparator />
                            <CommandGroup>
                                <CommandItem onSelect={() => onReset()} className="justify-center text-center">
                                    {__('main.data_table.toolbar.faceted_filter.clear_action')}
                                </CommandItem>
                            </CommandGroup>
                        </Activity>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
