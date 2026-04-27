import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { normalizeString } from '@/lib/utils';
import { lang } from '@erag/lang-sync-inertia/react';
import { memo } from 'react';

type Props = {
    exams: Record<string, number>;
};

const DashboardExamsSummaryCard = memo<Readonly<Props>>(({ exams }) => {
    const { __ } = lang();

    const examsObject = Object.entries(exams)
        .filter(([key]) => key !== 'total')
        .reduce(
            (acc, [key, value]) => {
                const removeCounterSuffix = key.replace(/_count$/, '');
                const normalizedKey = normalizeString(removeCounterSuffix);
                acc[normalizedKey] = value;

                return acc;
            },
            {} as Record<string, number>,
        );

    return (
        <Card className="w-full sm:col-span-2">
            <CardHeader>
                <CardTitle className="text-xl">{__('dashboard.exams_summary_card.title')}</CardTitle>
                <CardDescription>{__('dashboard.exams_summary_card.description')}</CardDescription>
            </CardHeader>

            <CardContent className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {Object.entries(examsObject).map(([examName, count]) => (
                    <div key={examName} className="flex items-center justify-between rounded-md bg-muted p-4">
                        <span className="font-medium">{examName}</span>
                        <span className="text-sm text-muted-foreground">
                            {count} {__('dashboard.exams_summary_card.recorded')}
                        </span>
                    </div>
                ))}
            </CardContent>

            <CardFooter className="mt-auto border-t text-sm text-muted-foreground">
                {exams['total']} {__('dashboard.exams_summary_card.recorded')}
            </CardFooter>
        </Card>
    );
});

export default DashboardExamsSummaryCard;
