import { Button } from '@/components/ui/button';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import type { RouteDefinition } from '@/wayfinder';
import { Link } from '@inertiajs/react';
import { ChartLineIcon, ClipboardPlusIcon } from 'lucide-react';

type Props = {
    createRoute: RouteDefinition<'get'>;
};

export function EmptyChartData({ createRoute }: Readonly<Props>) {
    return (
        <Empty className="mx-auto h-full max-w-3xl rounded-4xl border border-dashed bg-muted/30">
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
                <Button
                    variant="outline"
                    size="sm"
                    aria-label="Create new record of this type"
                    title="Create new record of this type"
                    asChild
                >
                    <Link href={createRoute} as="button" prefetch viewTransition>
                        <ClipboardPlusIcon aria-hidden size={16} /> Create
                    </Link>
                </Button>
            </EmptyContent>
        </Empty>
    );
}
