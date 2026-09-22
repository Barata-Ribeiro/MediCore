import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useTableContext } from '@/hooks/table-context';
import { lang } from '@erag/lang-sync-inertia/react';
import { Settings2Icon } from 'lucide-react';

export default function DataTableViewOptions() {
    const table = useTableContext();
    const { __ } = lang();
    return (
        <DropdownMenu>
            <DropdownMenuTrigger
                render={
                    <Button variant="outline" aria-label={__('main.data_table.column_visibility.label')}>
                        <Settings2Icon data-icon="inline-start" />
                        {__('main.data_table.column_visibility.action')}
                    </Button>
                }
            />
            <DropdownMenuContent align="end">
                <DropdownMenuGroup>
                    {table
                        .getAllLeafColumns()
                        .filter((column) => column.getCanHide())
                        .map((column) => (
                            <DropdownMenuCheckboxItem
                                key={column.id}
                                checked={table.state.columnVisibility[column.id] !== false}
                                onCheckedChange={(checked) => column.toggleVisibility(checked)}
                            >
                                {column.columnDef.meta?.label ?? column.id}
                            </DropdownMenuCheckboxItem>
                        ))}
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
