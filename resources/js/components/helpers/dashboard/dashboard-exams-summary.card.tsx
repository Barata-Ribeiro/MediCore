import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { normalizeString } from '@/lib/utils';
import { memo } from 'react';

type Props = {
    exams: Record<string, number>;
};

const DashboardExamsSummaryCard = memo<Readonly<Props>>(({ exams }) => {
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
        <Card className="col-span-2 w-full">
            <CardHeader>
                <CardTitle className="text-xl">Exams Summary</CardTitle>
                <CardDescription>A quick overview of your medical exams and their results.</CardDescription>
            </CardHeader>

            <CardContent className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {Object.entries(examsObject).map(([examName, count]) => (
                    <div key={examName} className="flex items-center justify-between rounded-md bg-muted p-4">
                        <span className="font-medium">{examName}</span>
                        <span className="text-sm text-muted-foreground">{count} recorded</span>
                    </div>
                ))}
            </CardContent>

            <CardFooter className="mt-auto border-t text-sm text-muted-foreground">
                {exams['total']} exams recorded
            </CardFooter>
        </Card>
    );
});

export default DashboardExamsSummaryCard;
