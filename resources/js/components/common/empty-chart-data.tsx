import { Button } from '@/components/ui/button';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import type { RouteDefinition } from '@/wayfinder';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import { ChartLineIcon, ClipboardPlusIcon } from 'lucide-react';

type Props = {
    createRoute: RouteDefinition<'get'>;
};

export function EmptyChartData({ createRoute }: Readonly<Props>) {
    const { __ } = lang();

    return (
        <Empty className="mx-auto h-full max-w-3xl rounded-4xl border border-dashed bg-muted/30">
            <EmptyHeader>
                <EmptyMedia variant="icon">
                    <ChartLineIcon aria-hidden />
                </EmptyMedia>
                <EmptyTitle>{__('main.chart_empty_state.title')}</EmptyTitle>
                <EmptyDescription>{__('main.chart_empty_state.description')}</EmptyDescription>
            </EmptyHeader>
            <EmptyContent>
                <Button
                    variant="outline"
                    size="sm"
                    aria-label={__('main.chart_empty_state.action_label')}
                    title={__('main.chart_empty_state.action_label')}
                    asChild
                >
                    <Link href={createRoute} as="button" prefetch viewTransition>
                        <ClipboardPlusIcon aria-hidden size={16} /> {__('main.chart_empty_state.action')}
                    </Link>
                </Button>
            </EmptyContent>
        </Empty>
    );
}
