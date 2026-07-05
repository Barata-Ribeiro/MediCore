import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { lang } from '@erag/lang-sync-inertia/react';
import type { Column, Table } from '@tanstack/react-table';
import { Settings2 } from 'lucide-react';
import type { ComponentProps, JSX } from 'react';
import { useMemo, useState } from 'react';

interface DataTableViewOptionsProps<TData> extends ComponentProps<typeof PopoverContent> {
    table: Table<TData>;
    disabled?: boolean;
}

export default function DataTableViewOptions<TData>({
    table,
    disabled,
    className,
    ...props
}: Readonly<DataTableViewOptionsProps<TData>>) {
    const { __ } = lang();

    const columns = useMemo(
        () => table.getAllColumns().filter((column) => column.accessorFn !== undefined && column.getCanHide()),
        [table],
    );

    return (
        <Popover>
            <PopoverTrigger asChild>
                <Button
                    aria-label={__('main.data_table.column_visibility.label')}
                    role="combobox"
                    variant="outline"
                    className="ml-auto hidden h-8 lg:flex"
                    disabled={disabled}
                >
                    <Settings2 aria-hidden />
                    {__('main.data_table.column_visibility.action')}
                </Button>
            </PopoverTrigger>
            <PopoverContent className={cn('w-44 p-0', className)} {...props}>
                <Command>
                    <CommandInput placeholder={__('main.data_table.column_visibility.search')} />
                    <CommandList>
                        <CommandEmpty>{__('main.data_table.column_visibility.no_columns')}</CommandEmpty>
                        <CommandGroup>
                            {columns.map((column) => (
                                <ColumnVisibilityItem<TData> key={column.id} column={column} />
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}

function ColumnVisibilityItem<TData>({ column }: Readonly<{ column: Column<TData, unknown> }>): JSX.Element {
    const [isVisible, setIsVisible] = useState<boolean>(column.getIsVisible());
    const [label] = useState<string>(column.columnDef.meta?.label ?? column.id);

    function handleToggleVisibility(): void {
        column.toggleVisibility(!isVisible);
        setIsVisible(!isVisible);
    }

    return (
        <CommandItem data-checked={isVisible} onSelect={handleToggleVisibility}>
            <span className="truncate">{label}</span>
        </CommandItem>
    );
}
