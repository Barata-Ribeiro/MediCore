import { Button } from '@/components/ui/button';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import type { RouteDefinition } from '@/wayfinder';
import { Link } from '@inertiajs/react';
import { ChartLineIcon } from 'lucide-react';

type Props = {
    createRoute: RouteDefinition<'get'>;
};

export function EmptyChartData({ createRoute }: Readonly<Props>) {
    return (
        <Empty className="border border-dashed">
            <EmptyHeader>
                <EmptyMedia variant="icon">
                    <ChartLineIcon aria-hidden />
                </EmptyMedia>
                <EmptyTitle>No Data</EmptyTitle>
                <EmptyDescription>
                    No data available to display the chart. Please add some data to visualize your insights.
                </EmptyDescription>
            </EmptyHeader>
            <EmptyContent>
                <Button variant="outline" size="sm" asChild>
                    <Link
                        href={createRoute}
                        aria-label="Create new record of this type"
                        title="Create new record of this type"
                        as="button"
                        prefetch
                        viewTransition
                    >
                        Create
                    </Link>
                </Button>
            </EmptyContent>
        </Empty>
    );
}
