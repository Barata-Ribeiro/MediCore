import DataTableFilterList from '@/components/table/data-table-filter-list';
import DataTableSortList from '@/components/table/data-table-sort-list';
import DataTableViewOptions from '@/components/table/data-table-view-options';
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupInput } from '@/components/ui/input-group';
import { useTableContext } from '@/hooks/table-context';
import { lang } from '@erag/lang-sync-inertia/react';

export default function DataTableToolbar() {
    const table = useTableContext();
    const { __ } = lang();
    return (
        <div role="toolbar" className="flex flex-wrap items-center gap-2">
            <form
                onSubmit={(event) => {
                    event.preventDefault();
                    const search = new FormData(event.currentTarget).get('search');
                    table.setGlobalFilter(typeof search === 'string' ? search.trim() : '');
                }}
            >
                <InputGroup>
                    <InputGroupInput
                        key={String(table.state.globalFilter)}
                        name="search"
                        defaultValue={String(table.state.globalFilter ?? '')}
                        placeholder={__('main.data_table.toolbar.search.placeholder')}
                        aria-label={__('main.data_table.toolbar.search.action')}
                    />
                    <InputGroupAddon align="inline-end">
                        <InputGroupButton type="submit" variant="secondary">
                            {__('main.data_table.toolbar.search.action')}
                        </InputGroupButton>
                    </InputGroupAddon>
                </InputGroup>
            </form>
            <DataTableFilterList />
            <DataTableSortList />
            <DataTableViewOptions />
        </div>
    );
}
