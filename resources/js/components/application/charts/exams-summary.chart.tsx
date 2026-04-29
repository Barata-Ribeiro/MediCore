import type { ChartConfig } from '@/components/ui/chart';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { normalizeString } from '@/lib/utils';
import { lang } from '@erag/lang-sync-inertia/react';
import { memo } from 'react';
import { Label, Pie, PieChart } from 'recharts';

type Props = {
    exams: Record<string, number>;
};

const ExamsSummaryChart = memo<Readonly<Props>>(({ exams }) => {
    const { __ } = lang();

    const examEntries = Object.entries(exams).filter(([key]) => key !== 'total');

    const rechartsData = examEntries.map(([key, value]) => {
        const examKey = key.replace(/_count$/, '');

        return {
            exam: examKey,
            count: value,
            fill: `var(--color-${examKey})`,
        };
    });

    const totalExams = exams['total'] ?? 0;
    const hasExamData = rechartsData.some(({ count }) => count > 0);

    const chartConfig = examEntries.reduce(
        (acc, [key], index) => {
            const examKey = key.replace(/_count$/, '');

            acc[examKey] = {
                label: normalizeString(examKey),
                color: `var(--chart-${(index % 6) + 1})`,
            };

            return acc;
        },
        { count: { label: 'Exams' } } as ChartConfig,
    );

    const pieData = hasExamData ? rechartsData : [{ exam: 'empty', count: 1, fill: 'var(--color-border)' }];

    return (
        <ChartContainer config={chartConfig} className="mx-auto aspect-square max-h-62.5">
            <PieChart>
                {hasExamData ? (
                    <ChartTooltip cursor={false} content={<ChartTooltipContent hideLabel nameKey="exam" />} />
                ) : null}
                <Pie data={pieData} dataKey="count" nameKey="exam" innerRadius={60} strokeWidth={5}>
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
                                            {totalExams.toLocaleString()}
                                        </tspan>
                                        <tspan
                                            x={viewBox.cx}
                                            y={(viewBox.cy ?? 0) + 24}
                                            className="fill-muted-foreground"
                                        >
                                            {hasExamData
                                                ? __('dashboard.exams_made_card.chart_label')
                                                : __('dashboard.exams_made_card.chart_empty')}
                                        </tspan>
                                    </text>
                                );
                            }

                            return null;
                        }}
                    />
                </Pie>
            </PieChart>
        </ChartContainer>
    );
});

export default ExamsSummaryChart;
