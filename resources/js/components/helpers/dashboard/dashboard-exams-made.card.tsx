import ExamsSummaryChart from '@/components/application/charts/exams-summary.chart';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { memo } from 'react';

type Props = {
    exams: Record<string, number>;
};

const DashboardExamsMadeCard = memo<Readonly<Props>>(({ exams }) => {
    return (
        <Card>
            <CardHeader>
                <CardTitle className="text-xl">Exams Made</CardTitle>
                <CardDescription>A chart showing the number all exams made by you.</CardDescription>
            </CardHeader>

            <CardContent className="flex-1 pb-0">
                <ExamsSummaryChart exams={exams} />
            </CardContent>

            <CardFooter className="mt-auto border-t text-sm text-muted-foreground">
                {(exams['total'] ?? 0).toLocaleString()} exams made
            </CardFooter>
        </Card>
    );
});

export default DashboardExamsMadeCard;
