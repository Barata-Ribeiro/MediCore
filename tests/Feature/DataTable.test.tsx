import { DataTable } from '@/components/table/data-table';
import * as tableHooks from '@/hooks/table';
import type { ColumnDef } from '@/types/data-table';
import { createInertiaApp, router } from '@inertiajs/react';
import { renderToString } from 'react-dom/server';
import { afterEach, expect, it, vi } from 'vite-plus/test';

const columns: ColumnDef<{ id: number; name: string }>[] = [
    { accessorKey: 'id', header: 'ID' },
    { accessorKey: 'name', header: 'Name', meta: { label: 'Name', variant: 'text' } },
];
const records = [
    { id: 12, name: 'Zulu' },
    { id: 11, name: 'Alpha' },
];

async function renderTable(query = '') {
    return createInertiaApp({
        page: {
            component: 'TableTest',
            url: `/records${query}`,
            version: null,
            clearHistory: false,
            encryptHistory: false,
            props: { errors: {}, lang: {}, auth: { locale: 'en', user: {} } },
        },
        render: renderToString,
        resolve: () => () => (
            <DataTable
                columns={columns}
                data={records}
                pagination={{
                    current_page: 2,
                    per_page: 5,
                    total: 12,
                    last_page: 3,
                    from: 6,
                    to: 7,
                    path: '/records',
                    first_page_url: '/records',
                    last_page_url: '/records?page=3',
                    links: [],
                }}
            />
        ),
        setup: ({ App, props }) => <App {...props} />,
    });
}

afterEach(() => vi.restoreAllMocks());

it('renders server rows during SSR without filtering, sorting or paginating them again', async () => {
    const query = new URLSearchParams({
        search: 'does not match',
        sorting: JSON.stringify([{ id: 'name', desc: false }]),
    });
    const result = await renderTable(`?${query}`);
    expect(result.body).toContain('Zulu');
    expect(result.body.indexOf('Zulu')).toBeLessThan(result.body.indexOf('Alpha'));
    expect(result.body).toContain('<table');
});

it('retains empty operators and resets pagination when applying filters', async () => {
    const hook = vi.spyOn(tableHooks, 'useAppTable');
    const visit = vi.spyOn(router, 'get').mockImplementation(() => {});
    await renderTable('?search=keep');
    const table = hook.mock.results.at(-1)?.value;
    const filters = [{ id: 'name', filterId: 'empty-name', operator: 'isEmpty', value: '', joinOperator: 'and' }];
    table?.setColumnFilters(filters);
    expect(visit).toHaveBeenCalledWith(
        '/records',
        expect.objectContaining({
            search: 'keep',
            filters: JSON.stringify(filters),
            page: 1,
            per_page: 5,
        }),
        expect.objectContaining({ preserveState: true }),
    );
});

it('preserves filters and search when sending multiple sort priorities', async () => {
    const hook = vi.spyOn(tableHooks, 'useAppTable');
    const visit = vi.spyOn(router, 'get').mockImplementation(() => {});
    const filters = [{ id: 'name', filterId: 'name', operator: 'includesString', value: 'a,b:c', joinOperator: 'and' }];
    await renderTable(`?${new URLSearchParams({ search: 'keep', filters: JSON.stringify(filters) })}`);
    const sorting = [
        { id: 'name', desc: true },
        { id: 'id', desc: false },
    ];
    hook.mock.results.at(-1)?.value.setSorting(sorting);
    expect(visit).toHaveBeenCalledWith(
        '/records',
        expect.objectContaining({
            search: 'keep',
            filters: JSON.stringify(filters),
            sorting: JSON.stringify(sorting),
            page: 1,
        }),
        expect.any(Object),
    );
});

it('resets to the first page when changing the page size', async () => {
    const hook = vi.spyOn(tableHooks, 'useAppTable');
    const visit = vi.spyOn(router, 'get').mockImplementation(() => {});
    await renderTable();
    hook.mock.results.at(-1)?.value.setPageSize(25);
    expect(visit).toHaveBeenCalledWith('/records', { page: 1, per_page: 25 }, expect.any(Object));
});
