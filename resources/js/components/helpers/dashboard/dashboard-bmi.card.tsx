import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { Field, FieldLabel } from '@/components/ui/field';
import { Progress } from '@/components/ui/progress';
import { cn } from '@/lib/utils';
import { edit as editMedicalFile } from '@/routes/medical-file';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import { FileTextIcon } from 'lucide-react';
import { memo } from 'react';

type Props = {
    bmi?: number | null;
    height: number | null;
    weight: number | null;
};

const BMI_PROGRESS_MIN = 16;
const BMI_PROGRESS_MAX = 40;

function getBMIProgressPercentage(bmi: number): number {
    if (bmi <= BMI_PROGRESS_MIN) {
        return 0;
    }

    if (bmi <= 18.5) {
        return ((bmi - BMI_PROGRESS_MIN) / (18.5 - BMI_PROGRESS_MIN)) * 25;
    }

    if (bmi <= 25) {
        return 25 + ((bmi - 18.5) / (25 - 18.5)) * 25;
    }

    if (bmi <= 30) {
        return 50 + ((bmi - 25) / (30 - 25)) * 25;
    }

    if (bmi <= BMI_PROGRESS_MAX) {
        return 75 + ((bmi - 30) / (BMI_PROGRESS_MAX - 30)) * 25;
    }

    return 100;
}

function getBMICategory(bmi: number): { label: string; style: string; percentage: number } {
    switch (true) {
        case bmi <= 18.4:
            return {
                label: 'underweight',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 18.5 && bmi <= 24.9:
            return {
                label: 'normal',
                style: cn('bg-success text-success-content'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 25 && bmi <= 29.9:
            return {
                label: 'overweight',
                style: cn('bg-warning text-warning-content'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 30 && bmi <= 34.9:
            return {
                label: 'obesity_class_1',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 35 && bmi <= 39.9:
            return {
                label: 'obesity_class_2',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
        default:
            return {
                label: 'obesity_class_3',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
    }
}

const DashboardBMICard = memo<Readonly<Props>>(({ bmi, height, weight }) => {
    const { __ } = lang();

    if (!bmi || !height || !weight || height <= 0 || weight <= 0) {
        return (
            <Empty className="border border-dashed">
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <FileTextIcon aria-hidden />
                    </EmptyMedia>
                    <EmptyTitle>{__('dashboard.bmi_card.empty.title')}</EmptyTitle>
                    <EmptyDescription>{__('dashboard.bmi_card.empty.message')}</EmptyDescription>
                </EmptyHeader>
                <EmptyContent>
                    <Button variant="outline" size="sm" asChild>
                        <Link href={editMedicalFile()} as="button" prefetch="hover">
                            {__('dashboard.bmi_card.empty.action')}
                        </Link>
                    </Button>
                </EmptyContent>
            </Empty>
        );
    }

    const bmiCategory = getBMICategory(bmi);
    const roundedBmi = Math.floor(bmi * 100) / 100;

    return (
        <Card className="w-full">
            <CardHeader>
                <CardTitle className="text-xl">{__('dashboard.bmi_card.card.title')}</CardTitle>
                <CardDescription>{__('dashboard.bmi_card.card.description')}</CardDescription>
            </CardHeader>

            <CardContent className="grid gap-4">
                <h3 className="text-center text-4xl font-bold">{roundedBmi}</h3>

                <div className="flex flex-col items-center">
                    <Badge className={bmiCategory.style}>
                        {__(`dashboard.bmi_card.card.category.${bmiCategory.label}`)}
                    </Badge>

                    <Field className="mt-2 w-full">
                        <FieldLabel htmlFor="progress-bmi" className="sr-only">
                            {__('dashboard.bmi_card.card.label')}
                        </FieldLabel>

                        <Progress
                            id="progress-bmi"
                            aria-label={__('dashboard.bmi_card.card.label')}
                            value={bmiCategory.percentage}
                            aria-readonly
                        />

                        <small className="relative flex w-full justify-between text-xs font-medium text-muted-foreground">
                            <span>{BMI_PROGRESS_MIN}</span>
                            <span>18.5</span>
                            <span>25</span>
                            <span>30</span>
                            <span>{BMI_PROGRESS_MAX}</span>
                        </small>

                        <div className="mt-3 flex justify-between text-sm text-muted-foreground">
                            <p className="m-0">
                                {__('dashboard.bmi_card.card.height')}:&nbsp; <span>{height ?? '0'}</span> cm
                            </p>
                            <p className="m-0">
                                {__('dashboard.bmi_card.card.weight')}:&nbsp; <span>{weight ?? '0'}</span> kg
                            </p>
                        </div>
                    </Field>
                </div>
            </CardContent>

            <CardFooter className="mt-auto border-t">
                <Button variant="secondary" size="sm" className="ml-auto" asChild>
                    <Link href={editMedicalFile()} as="button" prefetch="hover">
                        {__('dashboard.bmi_card.card.action')}
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
});

export default DashboardBMICard;
