import ExamsSummaryChart from '@/components/application/charts/exams-summary.chart';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { lang } from '@erag/lang-sync-inertia/react';
import { memo } from 'react';

type Props = {
    exams: Record<string, number>;
};

const DashboardExamsMadeCard = memo<Readonly<Props>>(({ exams }) => {
    const { __ } = lang();

    return (
        <Card className="w-full">
            <CardHeader>
                <CardTitle className="text-xl">{__('dashboard.exams_made_card.title')}</CardTitle>
                <CardDescription>{__('dashboard.exams_made_card.description')}</CardDescription>
            </CardHeader>

            <CardContent className="flex-1 pb-0">
                <ExamsSummaryChart exams={exams} />
            </CardContent>

            <CardFooter className="mt-auto border-t text-sm text-muted-foreground">
                {(exams['total'] ?? 0).toLocaleString()} {__('dashboard.exams_made_card.made')}
            </CardFooter>
        </Card>
    );
});

export default DashboardExamsMadeCard;
