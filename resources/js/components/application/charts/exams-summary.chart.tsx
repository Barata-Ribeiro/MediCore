import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { normalizeString } from '@/lib/utils';
import { memo, useMemo } from 'react';
import { Label, Pie, PieChart } from 'recharts';

type Props = {
    exams: Record<string, number>;
};

const ExamsSummaryChart = memo<Readonly<Props>>(({ exams }) => {
    const rechartsData = useMemo(() => {
        return Object.entries(exams)
            .filter(([key]) => key !== 'total')
            .map(([key, value]) => {
                const removeCounterSuffix = key.replace(/_count$/, '');
                const normalizedKey = normalizeString(removeCounterSuffix);

                return {
                    exam: normalizedKey,
                    count: value,
                    fill: `var(--chart-${(Object.keys(exams).indexOf(key) % 6) + 1})`,
                };
            });
    }, [exams]);

    const chartConfig = useMemo(() => {
        return Object.keys(exams).reduce(
            (acc, key, idx) => {
                const removeCounterSuffix = key.replace(/_count$/, '');
                const normalizedKey = normalizeString(removeCounterSuffix);

                acc[key] = { label: normalizedKey, color: `var(--chart-${(idx % 6) + 1})` };

                return acc;
            },
            {} as Record<string, { label: string; color: string }>,
        );
    }, [exams]);

    return (
        <ChartContainer config={chartConfig} className="mx-auto aspect-square max-h-62.5">
            <PieChart>
                <ChartTooltip cursor={false} content={<ChartTooltipContent hideLabel />} />
                <Pie data={rechartsData} dataKey="count" nameKey="exam" innerRadius={60} strokeWidth={5}>
                    <Label
                        content={({ viewBox }) => {
                            if (viewBox && 'cx' in viewBox && 'cy' in viewBox) {
                                return (
                                    <text x={viewBox.cx} y={viewBox.cy} textAnchor="middle" dominantBaseline="middle">
                                        <tspan
                                            x={viewBox.cx}
                                            y={viewBox.cy}
                                            className="fill-foreground text-3xl font-bold"
                                        >
                                            {(exams['total'] ?? 0).toLocaleString()}
                                        </tspan>
                                        <tspan
                                            x={viewBox.cx}
                                            y={(viewBox.cy ?? 0) + 24}
                                            className="fill-muted-foreground"
                                        >
                                            Visitors
                                        </tspan>
                                    </text>
                                );
                            }
                        }}
                    />
                </Pie>
            </PieChart>
        </ChartContainer>
    );
});

export default ExamsSummaryChart;
