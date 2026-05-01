import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import type { ChartConfig } from '@/components/ui/chart';
import {
    ChartContainer,
    ChartLegend,
    ChartLegendContent,
    ChartTooltip,
    ChartTooltipContent,
} from '@/components/ui/chart';
import type { ChartData } from '@/types';
import { lang } from '@erag/lang-sync-inertia/react';
import { format } from 'date-fns';
import { Area, AreaChart, CartesianGrid, XAxis } from 'recharts';

type Props = {
    chartData: ChartData[];
    total: number;
};

export default function GlucoseChart({ chartData, total }: Readonly<Props>) {
    const { __ } = lang();

    const rechartsData = chartData.map((row) => {
        const base: Record<string, unknown> = { date: row.x_axis_label };

        if (row.datasets && typeof row.datasets === 'object') {
            const entries = Object.entries(row.datasets);

            for (const element of entries) {
                const [metric, value] = element;
                base[metric] = (value as { data?: number }).data ?? null;
            }
        }

        return base;
    });

    const firstDatasets = chartData?.[0]?.datasets ?? {};
    const chartConfig = Object.keys(firstDatasets).reduce((acc, key, idx) => {
        const label = (firstDatasets as Record<string, { label?: string }>)[key]?.label ?? key;
        acc[key] = { label, color: `var(--chart-${(idx % 6) + 1})` };

        return acc;
    }, {} as ChartConfig);

    return (
        <Card className="mx-auto max-w-3xl">
            <CardHeader>
                <CardTitle>{__('glucose_pages.index.chart.title')}</CardTitle>
                <CardDescription>{__('glucose_pages.index.chart.description')}</CardDescription>
            </CardHeader>
            <CardContent>
                <ChartContainer config={chartConfig}>
                    <AreaChart
                        accessibilityLayer
                        data={rechartsData}
                        margin={{ left: 12, right: 12, top: 12 }}
                        stackOffset="expand"
                    >
                        <CartesianGrid vertical={false} />
                        <XAxis
                            dataKey="date"
                            tickLine={false}
                            axisLine={false}
                            tickMargin={8}
                            interval="preserveStartEnd"
                            tickFormatter={(value) => format(new Date(String(value)), 'PPP')}
                        />
                        <ChartTooltip
                            cursor={false}
                            content={
                                <ChartTooltipContent
                                    indicator="line"
                                    labelFormatter={(label) => format(new Date(String(label)), 'PPP')}
                                />
                            }
                        />
                        {Object.entries(chartConfig).map(([key]) => (
                            <Area
                                key={key}
                                dataKey={key}
                                type="natural"
                                fill={`var(--color-${key})`}
                                stroke={`var(--color-${key})`}
                                stackId="a"
                            />
                        ))}
                        <ChartLegend content={<ChartLegendContent />} />
                    </AreaChart>
                </ChartContainer>
            </CardContent>
            <CardFooter className="border-t">
                <p className="text-sm text-muted-foreground">
                    {__('glucose_pages.index.chart.footer_total_label')} <strong>{total}</strong>
                </p>
            </CardFooter>
        </Card>
    );
}
