import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import type { ChartConfig } from '@/components/ui/chart';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import type { ChartData } from '@/types/ui';
import { lang } from '@erag/lang-sync-inertia/react';
import { format } from 'date-fns';
import { memo, useMemo } from 'react';
import { CartesianGrid, LabelList, Line, LineChart, XAxis } from 'recharts';

type Props = {
    chartData: ChartData[];
    total: number;
};

const VitaminD3Chart = memo(({ chartData, total }: Readonly<Props>) => {
    const { __ } = lang();

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
                <CardTitle>{__('vitamin_d3_pages.index.chart.title')}</CardTitle>
                <CardDescription>{__('vitamin_d3_pages.index.chart.description')}</CardDescription>
            </CardHeader>
            <CardContent>
                <ChartContainer config={chartConfig}>
                    <LineChart
                        accessibilityLayer
                        data={rechartsData}
                        margin={{ top: 20, left: 12, right: 12 }}
                        stackOffset="expand"
                    >
                        <CartesianGrid vertical={false} />
                        <XAxis
                            dataKey="date"
                            tickLine={false}
                            axisLine={false}
                            tickMargin={8}
                            tickFormatter={(value) => format(new Date(String(value)), 'PPP')}
                            interval="preserveStartEnd"
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
                            <Line
                                key={key}
                                type="natural"
                                dataKey={key}
                                stroke={`var(--color-${key})`}
                                strokeWidth={2}
                                dot={{ fill: `var(--color-${key})` }}
                                activeDot={{ r: 6 }}
                            >
                                <LabelList
                                    position="top"
                                    offset={12}
                                    className="fill-foreground"
                                    fontSize={12}
                                    formatter={(value) => `${value} ng/mL`}
                                />
                            </Line>
                        ))}
                    </LineChart>
                </ChartContainer>
            </CardContent>
            <CardFooter className="border-t">
                <p className="text-sm text-muted-foreground">
                    {__('vitamin_d3_pages.index.chart.footer_total_label')} <strong>{total}</strong>
                </p>
            </CardFooter>
        </Card>
    );
});

export default VitaminD3Chart;
