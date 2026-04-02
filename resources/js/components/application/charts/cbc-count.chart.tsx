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
import { format } from 'date-fns';
import { memo, useMemo } from 'react';
import { Area, AreaChart, CartesianGrid, XAxis } from 'recharts';

type Props = {
    chartData: ChartData[];
    total: number;
};

const CbcCountChart = memo(({ chartData, total }: Readonly<Props>) => {
    const rechartsData = useMemo(() => {
        return chartData.map((row) => {
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
    }, [chartData]);

    const chartConfig = useMemo(() => {
        const firstDatasets = chartData?.[0]?.datasets ?? {};
        const keys = Object.keys(firstDatasets);

        return keys.reduce((acc, key, idx) => {
            const label = (firstDatasets as Record<string, { label?: string }>)[key]?.label ?? key;
            acc[key] = { label, color: `var(--chart-${(idx % 6) + 1})` };

            return acc;
        }, {} as ChartConfig);
    }, [chartData]) satisfies ChartConfig;

    return (
        <Card className="mx-auto max-w-3xl">
            <CardHeader>
                <CardTitle>Complete Blood Count</CardTitle>
                <CardDescription>
                    Check your last 5 complete blood count results and see how your blood cell levels have changed over
                    time.
                </CardDescription>
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
                    Total complete blood count results: <strong>{total}</strong>
                    <br />
                    Note: The chart displays only some of the available metrics. For a complete view of all your blood
                    count metrics, please refer to the table below.
                </p>
            </CardFooter>
        </Card>
    );
});

export default CbcCountChart;
